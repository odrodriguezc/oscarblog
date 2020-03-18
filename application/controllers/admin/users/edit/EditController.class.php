<?php

class EditController
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
            /** Redirection vers le referer */
            header("location: {$_SERVER['HTTP_REFERER']}");

		/**
		 * usermodel
		 * instance du model users et stackage dans une variable
		 */
		 $userModel = new UsersModel();

		/**
		 * @var user array whit information of a particular user
		 * @var roles array with the list of users roles 
		 * 
		 */
		$user = $userModel->find($queryFields['id']);
		$gateway['roles'] = $userModel->role;

		/**
		 * Instance du formulair user et passage de l'information  de l'utilisateur dans la vue à l'exception du password pour des raison de securité 
		 */
		$form = new UsersForm();
		$form->bind(array('id'=>$user['id'],
						'username' => $user['username'], 
						'firstname' => $user['firstname'],
						'lastname' => $user['lastname'],
						'email' => $user['email'],
						'phone' => $user['phone'],
						'intro' => $user['intro'],
						'profile' => $user['profile'],
						'role' => $user['role'],
						'status' => $user['status'],
                        'originalAvatar' => $user['avatar']));
		$gateway['_form'] = $form;
		
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
        
        if ($userSession->isAuthorized([3])==false)
            /** Redirection vers le dashboard */
            $http->redirectTo('/admin/');

        $usersModel = new UsersModel();
        //recuperation de l'utilisateur en BD pour en extraire apres le password
        $user = $usersModel->find($formFields['id']);

		try
        {
            /** Récupération de la photo originale */
            if ($http->hasUploadedFile('avatar')) {
                $avatar = $http->moveUploadedFile('avatar','/assets/images/users/'); //On déplace la photo à l'endroit désiré(le chemin est relatif par rapport au dossier www)et on stocke dans la variable avatar le nom du fichier
                /** On supprime l'ancienne image */
                if($formFields['originalAvatar']!=NULL && file_exists(WWW_PATH.'/assets/images/users/'.     $formFields['originalAvatar']))
                {
                    unlink(WWW_PATH.'/assets/images/users/'.$formFields['originalAvatar']);
                }
            } else {
                $avatar = $formFields['originalAvatar']; // Le nom de l'image reste le nom qui était là à l'origine
            }
            
            /** Vérification des données 
             * C'est le contrôleur qui contrôle les données et non le modèle !
             * Si les champs sont vides on lance un exception pour réafficher le formulaire et les erreurs !
            */
             /** On vérifie que tous les champs sont remplis sauf */
            foreach($formFields as $index=>$formField)
            {
                if (empty($formField) && ($index != 'intro' || $index != 'profile' ))
                    throw new DomainException('Merci de remplir de remplir le champ '.$index .' !');
            }

            //passage du meme password
            $passwordHash = $user['passwordHash'];


            /** Enregistrer les données dans la base de données */
            $usersModel->update($formFields['username'], 
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
                                $formFields['id']);
            
            /** Ajout du flashbag */
            $flashbag = new Flashbag();
            $flashbag->add('L\'utilisateur a bien été modifiée');
            
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
 
            return   ['_form' => $form,
                    'roles' => $usersModel->role
            ];
        
        }
    }
}