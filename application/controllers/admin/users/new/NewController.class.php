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
    	/*
    	 * Méthode appelée en cas de requête HTTP POST
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
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
			
			  /** On vérifie que tous les champs sont remplis sauf */
			  foreach($formFields as $index=>$formField)
			  {
				  if (empty($formField) && ($index != 'intro' || $index != 'profile' ))
					  throw new DomainException('manque'.$index.'' );
			  }
  
			  /**Verification de l'egalité des mot de passe */
			  /*if ($formField['password'] != $formField['confirmPassword']) {
				  throw new DomainException('Les mot de passe doit etre identique !');
			  }*/

			  /**Chifrage du mot de pass avec la methode HASH */
			  $passwordHash = password_hash($formFields['password'], PASSWORD_DEFAULT);
  
			  $registeredAtDate = date('Y-m-d');

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
								$avatar,
								$registeredAtDate
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