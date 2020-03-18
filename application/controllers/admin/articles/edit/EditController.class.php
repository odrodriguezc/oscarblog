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
                $picture = $formFields['originalpicture']; // Le nom de l'image reste le nom qui était là à l'origine
            }
            
            /** Vérification des données 
             * C'est le contrôleur qui contrôle les données et non le modèle !
             * Si les champs sont vides on lance un exception pour réafficher le formulaire et les erreurs !
            */
             /** On vérifie que tous les champs sont remplis sauf */
            foreach($formFields as $index=>$formField)
            {
                if (empty($formField) && ($index != 'summary' || $index != 'picture' ))
                    throw new DomainException('Merci de remplir tous les champs !');
            }
            
            /** Enregistrer les données dans la base de données */
            $articlesModel = new ArticlesModel();
            $articlesModel->update($formFields['id'],
                                $formFields['title'], 
                                $formFields['metaTitle'],
                                $formFields['summary'],
                                $formFields['content'],
                                $picture
                                );
            
            /** Ajout du flashbag */
            $flashbag = new Flashbag();
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
            $form->setErrorMessage($exception->getMessage());
 
            return   ['_form' => $form
            ];
        
        }
    }
}