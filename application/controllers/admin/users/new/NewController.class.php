<?php

class NewController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP GET
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
    	 */

		/** 
		  * UserSession - instance de la classe session
		  * 
		  * - isAutheticated va nous permettre de savoir si l'utilisateur est connecté 
		*/
		$userSession = new UserSession();
		$flashbag = new FlashBag();
		if ($userSession->isAuthenticated()==false) 
			/** Redirection vers le login */
			$http->redirectTo('/login/');
		
		if ($userSession->isAuthorized([3])==false)
		{
			$flashbag->add("Vous n'etes pas autorisé");
			$http->redirectTo('/admin/');
		}
			
		/**
		 * @var Usermodel $usermodel
		 * instance du model users et stackage dans une variable
		 */
		 $userModel = new UsersModel();

		 /**
		  * 
		  *
		  * @var array $roles array with the list of users roles
		  * @var UserForm $_form Array contenat les variables fournis par la class UsersForm
		  */
		$gateway = ['roles' => $userModel->role,
					'_form' => new UsersForm(),
		];


		return $gateway;
    }

    public function httpPostMethod(Http $http, array $formFields)
    {

		/** 
		  * UserSession - instance de la classe session
		  * 
		  * - isAutheticated va nous permettre de savoir si l'utilisateur est connecté 
		*/
		$userSession = new UserSession();
		$flashbag = new FlashBag();
		if ($userSession->isAuthenticated()==false) 
			/** Redirection vers le login */
			$http->redirectTo('/login/');
		
		try
		{
			 /** Image uploadée
            *   On la déplace sinon on affecte à NULL pour la saisie en base
            */
            if ($http->hasUploadedFile('avatar'))
                $avatar = $http->moveUploadedFile('avatar','/assets/images/users/');
            else 
				$avatar = NULL;
			
				//On vérifie que tous les champs obligatoires sont remplis 
				$validator = new DataValidation();
				$validator->obligatoryFields(['username' => $formFields['username'], 
												'email' => $formFields['email'], 
												'password' => $formFields['password'], 
												'confirmPassword' => $formFields['confirmPassword']]
				); 
				
				//securisation de la donné 
				$data = $validator->formFilter($formFields);

				 //username 
				$validator->username($data['username']);

				// format attendu : courriel
				$validator->email($data['email']);

				//password
				if($validator->password($data['password'], $data['confirmPassword']))  
					/**Chifrage du mot de pass avec la methode HASH */
					$passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
				//phone 
				if ($data['phone']!='')
					$validator->phone($data['phone']);
				//intro 
				if ($data['intro']!='')
					$validator->lengtOne($data['intro'],'Intro',500);
				//profile
				if ($data['profile']!='')
					$validator->lengtOne($data['profile'],'Profil',5000);
				
				/**
				 * verification d'unicité du username et du mail 
				 * 
				 * - Requete pour recouperer les users qu'on soit le même username soit le même email mais qui n'ont pas le même id
				 * 
				 * - Si l'on obtient au moins un user on boucle pour verifier si c'est le username et/ou le email qui est déjà a été trouvé
				 * 
				 * - On envoie des erreur en fonction de mail ou username répeté
				 * @author ODRC
				 */
				$usersModel = new UsersModel();
				$matchUsers = $usersModel->findByUsernameOrEmail($data['username'],$data['email']);

				if ($matchUsers!=false)
				{
					foreach ($matchUsers as $key => $matchUser) 
					{
						if ($matchUser['username'] === $data['username'])
                        $validator->addError("le nom d'utilisateur \"{$data['username']}\" est déjà existant");
						
						if ($matchUser['email'] === $data['email'])
                        $validator->addError("le mail \"{$data['email']}\" est déjà existant");                       
					}
				}

				if (empty($validator->getErrors()) != true)
					throw new DomainException("DExc - Erreur de validation des champs du formulaire");
					
			 	 /** Enregistrer les données dans la base de données */
			  	$usersModel->add($data['username'], 
								$data['firstname'],
								$data['lastname'],
								$data['email'],
								$passwordHash,
								$data['phone'],
								$data['intro'],
								$data['profile'],
								$data['role'],
								$data['status'],
								$avatar
								);
			  
			/** Ajout du flashbag */
			$flashbag->add('L\'utilisateur a bien été ajouté');
			
			/** Redirection vers la liste */
			$http->redirectTo('admin/users/');

		}
		catch(DomainException $exception)
		{
		/** DomainException est un type d'exception prédéfinie par PHP (valeur en dehors des limites selon la doc, on l'utilise donc ici pour ça !)
		 *   On a choisi ce type d'exception dans l'arbre généalogique des exceptions fournies par PHP. On aurait pu faire notre propre class
		 *   Exemple : class FormValideException extends Exception {}
		 */
		
			/** Réaffichage du formulaire avec un message d'erreur. */
			$form = new UsersForm();
			/** On bind nos données $_POST ($formFields) avec notre objet formulaire */
			$form->bind($formFields);
			//liste d'erreur dans le formulair avec le message pour chaq'un
			$form->setErrorMessage($validator->getErrors());
			//erreur lancé dans l'exeption
            $form->addError($exception->getMessage());
			
			$usersModel = new UsersModel();
			return  ['_form' => $form,
					'roles' => $usersModel->role
			 		];

		}
    }
}