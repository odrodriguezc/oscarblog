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
        $flashbag = new FlashBag();
		if ($userSession->isAuthenticated()==false) 
			/** Redirection vers le login */
			$http->redirectTo('/login/');
        
        if ($userSession->isAuthorized([1,2,3])==false)
        {
            $flashbag->add('Vous n\'etes pas autorisé');
            $http->redirectTo('/admin/');
        }

        $userModel = new UsersModel();

		/**
		 * @var user utilisateur connecté
		 * @var roles liste de roles  
		 * 
		 */
		$user = $userModel->find($userSession->getId());
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
                        'originalAvatar' => $user['avatar']
                    ));
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
        $flashbag = new FlashBag();
		if ($userSession->isAuthenticated()==false) 
			/** Redirection vers le login */
			$http->redirectTo('/login/');
        
        if ($userSession->isAuthorized([1,2,3])==false)
        {
            $flashbag->add('Vous n\'etes pas autorisé');
            $http->redirectTo('/admin/');
        }


        $usersModel = new UsersModel();
        //recuperation de l'utilisateur à modifier en BDD 
        $selectedUser = $usersModel->find($userSession->getId());
		try
        {            
           //On vérifie que tous les champs obligatoires sont remplis 
            $validator = new DataValidation();
            $validator->obligatoryFields(['username' => $formFields['username'], 
                                             'email' => $formFields['email'],
                                             'role' => $formFields['role'],
                                             'status' => $formFields['status'],
                                             'currentPassword' =>$formFields['currentPassword'],
                                             'password' =>$formFields['password'],
                                             'confirmPassword' =>$formFields['confirmPassword']
                                        ]); 

            //securisation de la donné 
            $data = $validator->formFilter($formFields);
            //username 
            $validator->username($data['username']);
            // format attendu : courriel
            $validator->email($data['email']);
            //role
            $validator->role($data['role'], $usersModel->role);
            //status
            $validator->status($data['status']);
            //phone 
            if ($data['phone']!='')
                $validator->phone($data['phone']);
            //intro 
            if ($data['intro']!='')
                $validator->lengtOne($data['intro'],'Intro',500);
            //profile
            if ($data['profile']!='')
                $validator->lengtOne($data['profile'],'Profil',5000);
            
           /**current Password check */
           if(!$selectedUser || !password_verify($data['password'],$selectedUser['passwordHash']))
                $validator->addError('Mot de passe incorrect !');

            //password
            if($validator->password($data['password'], $data['confirmPassword']))  
                /**Chifrage du mot de pass avec la methode HASH */
                $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

            /**
             * verification d'unicité du username et du mail 
             * 
             * - Requete pour recouperer les users qu'on soit le même username soit le même email mais qui n'ont pas le même id
             * - Si l'on obtient au moins un user on boucle pour verifier si c'est le username et/ou le email qui est déjà a été trouvé
             * - On envoie des erreur en fonction de mail ou username répeté
             * 
             * @author ODRC
             *  
             * */ 
            $matchUsers = $usersModel->findByUsernameOrEmail($data['username'],$data['email'],$selectedUser['id']);

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

            /**
             * image upload
             * @author ODRC
             */
            if ($http->hasUploadedFile('avatar'))
            {
                $avatar = new Upload($_FILES['avatar']);
                /*@todo - bug dans la classe Upload qui ne charge pas la liste d'images supportées*/
                if ($avatar->file_is_image)
                {
                    if ($avatar->uploaded)
                    {
                        //process taille original
                        $uniqName = uniqid('userAvatar');
						$avatar->file_new_name_body = "bg_".$uniqName;
						$avatar->file_overwrite = true;
						$avatar->process(WWW_PATH."/assets/images/users/");
                        if ($avatar->processed)
                        {
                            //nom pour la bdd
                            $avatarName = $uniqName.'.'.$avatar->file_dst_name_ext;
                            
							//petite taille - prefixe 'lt' 
							$avatar->file_new_name_body = "sm_".$uniqName;
							//$avatar->file_name_body_pre = 'lt';
							$avatar->image_resize =true;
							$avatar->image_x = 400;
							$avatar->image_ratio_y = true;
							$avatar->file_overwrite = true;
							$avatar->process(WWW_PATH."/assets/images/users/");
                        } else{
                            $validator->addError($avatar->error);
                        }
    
                    } else {
                        $validator->addError($avatar->error);
                    }

                } else{
                    $validator->addError("ceci n'est pas une immage");
                    $avatarName = $formFields['originalAvatar'];
                }

            } else {
                $avatarName = $formFields['originalAvatar'];
            }

            if (empty($validator->getErrors()) != true)
            {
                //supprimer images
                if ($avatarName != $formFields['originalAvatar'] && file_exists(WWW_PATH."/assets/images/users/bg_".$avatarName))
                    unlink(WWW_PATH."/assets/images/users/bg_".$avatarName);
                if ($avatarName != $formFields['originalAvatar'] && file_exists(WWW_PATH."/assets/images/users/sm_".$avatarName))
                    unlink(WWW_PATH."/assets/images/users/sm_".$avatarName);

                throw new DomainException("DExc - Erreur de validation des champs du formulaire");
            }

            //supprimer l'ancien avatar
            if ($formFields['originalAvatar']!=NULL && file_exists(WWW_PATH.'\assets\images\users\bg_' .$formFields['originalAvatar']))
                unlink(WWW_PATH.'/assets/images/users/bg_'.$formFields['originalAvatar']);

            if ($formFields['originalAvatar']!=NULL && file_exists(WWW_PATH.'/assets/images/users/sm_'.$formFields['originalAvatar']))
                unlink(WWW_PATH."/assets/images/users/sm_{$formFields['originalAvatar']}");


            /** Enregistrer les données dans la base de données */
            $usersModel->update($data['username'], 
                                $data['firstname'],
                                $data['lastname'],
                                $data['email'],
                                $passwordHash,
                                $data['phone'],
                                $data['intro'],
                                $data['profile'],
                                $data['role'],
                                $data['status'],
                                $avatarName,
                                $data['id']);
            
            /** Ajout du flashbag */
            $flashbag->add('L\'utilisateur a bien été modifiée');
            
            /** Redirection vers la liste */
            $http->redirectTo('/admin/profile/');
        }
         catch(DomainException $exception)
        {
            /** Réaffichage du formulaire avec un message d'erreur. */
            $form = new UsersForm();
            /** On bind nos données $_POST ($formFields) avec notre objet formulaire */
            $form->bind($formFields);
           //liste d'erreur dans le formulair avec le message pour chaq'un
			$form->setErrorMessage($validator->getErrors());
			//erreur lancé dans l'exeption
            $form->addError($exception->getMessage());
 
        return      ['_form' => $form,
                        'roles' => $usersModel->role
                    ];
        
        }
    }
}