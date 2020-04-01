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
        
        if ($userSession->isAuthorized([3])==false)
            /** Redirection vers le referer */
            header("location: {$_SERVER['HTTP_REFERER']}");


        
        $articlesModel = new ArticlesModel();

		$article = $articlesModel->find($queryFields['id']);


		/**
		 * Instance du formulair article et passage de l'information  de l'utilisateur dans la BD 
		 */
		$form = new ArticlesForm();
        $form->bind(array('id'=> $article['id'],
                        'title'=> $article['title'],
                        'metaTitle'=> $article['metaTitle'],
                        'summary'=> $article['summary'],
                        'published'=> $article['published'],
                        'createdAt'=> $article['createdAt'],
                        'publishedAt'=> $article['publishedAt'],
                        'content'=> $article['content'],
                        'picture'=> $article['picture'],
                        'like'=> $article['like'],
                        'dislike'=> $article['dislike'],
                        'share'=> $article['share'],
                        'author_id'=> $article['author_id'],
                        'originalPicture' => $article['picture']
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
        $flashbag = new Flashbag();
		if ($userSession->isAuthenticated()==false) 
			/** Redirection vers le login */
			$http->redirectTo('/login/');
        
        if ($userSession->isAuthorized([3])==false)
            /** Redirection vers le dashboard */
            $http->redirectTo('/admin/');

		try
        {
            /** Récupération de la photo originale */

            if ($http->hasUploadedFile('picture')) {
                $picture = $http->moveUploadedFile('picture','/assets/images/articles/'); //On déplace la photo à l'endroit désiré(le chemin est relatif par rapport au dossier www)et on stocke dans la variable picture le nom du fichier
                /** On supprime l'ancienne image */
                if($formFields['originalPicture']!=NULL && file_exists(WWW_PATH.'/assets/images/articles/'.$formFields['originalPicture'])){
                    unlink(WWW_PATH.'/assets/images/users/'.$formFields['originalPicture']);
                }
            } else {
                $picture = $formFields['originalPicture']; // Le nom de l'image reste le nom qui était là à l'origine
            }
            
           //instance de la classe DataValidation
			$validator = new DataValidation;

			//verifications des champs obligatoires
			$validator->obligatoryFields(['title' => $formFields['title'],
											'summary' => $formFields['summary'],
											'content' => $formFields['content']
											]);

            //securisation de la donné 
			$data = $validator->formFilter($formFields);

			//longueur des champs
			$validator->lengtOne($data['title'], 'Titre', 255);	
			$validator->lengtOne($data['metaTitle'], 'Soustitre', 255);
			$validator->lengtOne($data['summary'], 'Resumé', 500);
			$validator->lengtOne($data['content'], 'Contenu', 50000);

            //verification d'unicité du titre 
            $articlesModel = new articlesModel();
			if ($articlesModel->findByNewTitle($data['title'],$data['id'])!=false)
                $validator->addError("le nouveau titre {$data['title']} est déjà existant");
                
            if (empty($validator->getErrors())==false)
                throw new DomainException("DExc - Erreur de validation des champs du formulaire");
            
            /** Enregistrer les données dans la base de données */
            $articlesModel = new ArticlesModel();
            $articlesModel->update($data['id'],
                                $data['title'], 
                                $data['metaTitle'],
                                $data['summary'],
                                $data['content'],
                                $picture
                                );
            
            /** Ajout du flashbag */
            $flashbag->add('L\'article a bien été modifiée');
            
            /** Redirection vers la liste */
            $http->redirectTo('admin/articles/');
        }
         catch(DomainException $exception)
        {
            /** DomainException est un type d'exception prédéfinie par PHP (valeur en dehors des limites selon la doc, on l'utilise donc ici pour ça !)
             *   On a choisi ce type d'exception dans l'arbre généalogique des exceptions fournies par PHP. On aurait pu faire notre propre class
             *   Exemple : class FormValideException extends Exception {}
             */

            /** Réaffichage du formulaire avec un message d'erreur. */
			$form = new ArticlesForm();
			/** On bind nos données $_POST ($formFields) avec notre objet formulaire */
			$form->bind($formFields);
			//liste d'erreur dans le formulair avec le message pour chaq'un
			$form->setErrorMessage($validator->getErrors());
			//erreur lancé dans l'exeption
            $form->addError($exception->getMessage());

 
            return   ['_form' => $form];
        
        }
    }
}