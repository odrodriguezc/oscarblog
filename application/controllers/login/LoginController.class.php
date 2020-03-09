<?php

class LoginController
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
		if ($userSession->isAuthenticated()==true) 
			/** Redirection vers l'admin */
			$http->redirectTo('/admin/');
		else
		
		
		return ['_raw_template' => '',
				'errorMessage' => ''
		];

    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP POST
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
    	 */

        $userSession = new UserSession();
		if ($userSession->isAuthenticated()==true) 
			/** Redirection vers l'admin */
			$http->redirectTo('/admin/');
		else
		

		try
        {
            $userModel = new UsersModel();

            /** On vérifie que email et password fournis */
            if($formFields['email'] == '' || $formFields['password']=='')
                throw new DomainException('Merci de remplir tous les champs !');

			/**
			 * Email check 
			 * */
			if(filter_var($formFields['email'], FILTER_VALIDATE_EMAIL) == false)
				throw new DomainException('Email non valide !!!');
			else 
				/** Recherche de l'utilisateur en BD   */
				$user = $userModel->findByEmail($formFields['email']);

			/**Password check */
            if(!$user || !password_verify($formFields['password'],$user['passwordHash']))
                throw new DomainException('Email ou mot de passe incorrects !');

            /** Update last-login */
            $userModel->updateLogin($user['id']);

            /** Construction de la session utilisateur*/
            $userSession = new UserSession();
            $userSession->create
            (
				$user['id'],
				$user['username'],
                $user['firstname'],
                $user['lastname'],
				$user['email'],
				$user['role'],
				$user['status']
            );

            /** Flashbag */
            $flashbag = new Flashbag();
            $flashbag->add('Vous êtes maintenant connecté !');

            /** Redirection vers la liste des clients */
            $http->redirectTo('/admin/');
        }
        catch(DomainException $exception)
        {
             /** DomainException est un type d'exception prédéfinie par PHP (valeur en dehors des limites selon la doc, on l'utilise donc ici pour ça !)
             *   On a choisi ce type d'exception dans l'arbre généalogique des exceptions fournies par PHP. On aurait pu faire notre propre class
             *   Exemple : class FormValideException extends Exception {}
             */

            /** Réaffichage du formulaire avec un message d'erreur. */
            $form = new usersForm();
            /** On bind nos données $_POST ($formFields) avec notre objet formulaire */
            $form->bind($formFields);
            $form->setErrorMessage($exception->getMessage());
            
            return [ 
				'_form' => $form,
				'_raw_template' => '' 
            ]; 
		}
	}
}