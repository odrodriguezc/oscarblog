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
				if ($formFields['username']==='' || $formFields['email']==='' || $formFields['password']==='' || $formFields['confirmPassword']==='') 
					throw new DomainException('Merci de remplir de remplir le champ tous les champs obligatoires: mail, username et mot de passe');
				
				//securisation de la donné 
				$data = DataValidation::formFilter($formFields);

				 //username 
				if (DataValidation::usernameValidate($data['username'])===false)
					throw new DomainException('Le nom d\'utilisateur est invalide. Il doit contenir entre 5 et 36 caracteres alphanumeriques, pas d\'espaces, pas de symboles especiaux');
 
				// format attendu : courriel
				if (!filter_var( $data['email'], FILTER_VALIDATE_EMAIL))
					throw new DomainException ('Le courriel n\'est pas valide. Il doit être au format unnom@undomaine.uneextension.');
 
				//phone 
				if ($data['phone']!='')
					if (DataValidation::phoneValidate($data['phone'])===false)
						throw new DomainException('Le numero de telephonoe n\'est pas valide');

			  	/**Verification de l'egalité des mot de passe */
				if (DataValidation::passwordValidation($data['password'], $data['confirmPassword'])===false)
			  	{
					throw new DomainException('Les mot de passe doit etre identique !');
				} else {
					 /**Chifrage du mot de pass avec la methode HASH */
					 $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
				}
				  

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