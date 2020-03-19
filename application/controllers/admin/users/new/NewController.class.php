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
		if ($userSession->isAuthenticated()==false) 
			/** Redirection vers le login */
			$http->redirectTo('/login/');
		
		if ($userSession->isAuthorized([3])==false)
		/** Redirection vers le dashboard */
		$http->redirectTo('/login/');

		/**
		 * usermodel
		 * instance du model users et stackage dans une variable
		 */

		 $userModel = new UsersModel();

		 /**
		  * 
		  *
		  * @var roles array with the list of users roles
		  * @var _form Array contenat les variables fournis par la class UsersForm
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
		if ($userSession->isAuthenticated()==false) 
			/** Redirection vers le login */
			$http->redirectTo('/login/');
		else
		
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
				DataValidation::obligatoryFields(['username' => $formFields['username'], 
												'email' => $formFields['email'], 
												'password' => $formFields['password'], 
												'confirmPassword' => $formFields['confirmPassword']]
				); 
				
				//securisation de la donné 
				$data = DataValidation::formFilter($formFields);

				 //username 
				DataValidation::username($data['username']);
				
				//verification d'unicité du username
				$usersModel = new usersModel();
				if ($usersModel->findByUsername($data['username'])!=false)
					throw new DomainException("le nom d'utilisateur {$data['username']} est déjà existant");

				// format attendu : courriel
				DataValidation::email($data['email']);

				//verification d'unicité du username
				if ($usersModel->findByEmail($data['email'])!=false)
					throw new DomainException("le mail {$data['email']} est déjà existant");

				//password
				if(DataValidation::password($data['password'], $data['confirmPassword']))  
					/**Chifrage du mot de pass avec la methode HASH */
					$passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
					
				//phone 
				if ($data['phone']!='')
					DataValidation::phone($data['phone']);
				
			 	 /** Enregistrer les données dans la base de données */
			  	$usersModel = new UsersModel();
			  	$usersModel->add($formFields['username'], 
								$formFields['firstname'],
								$formFields['lastname'],
								$formFields['email'],
								$passwordHash,
								$formFields['phone'],
								$formFields['intro'],
								$formFields['profile'],
								$formFields['role'],
								$formFields['status'],
								$avatar
								);
			  
			/** Ajout du flashbag */
			$flashbag = new Flashbag();
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
			 $form->setErrorMessage($exception->getMessage());
			
			 $usersModel = new UsersModel();
			 return   ['_form' => $form,
					 'roles' => $usersModel->role
			 ];

		}
    }
}