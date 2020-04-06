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
            
        $articlesModel = new ArticlesModel();
        $validator = new DataValidation();
		$artId = $validator->inputFilter($queryFields['id']);
        $article = $articlesModel->find($artId);

        //Si l'utilisateur n'est pas autorisé
		if ($userSession->isAuthorized([2,3])==false)
		{
			//recherche de l'article à editer
			if ($article==false)
			{
				$flashbag->add("L'article n'a pas été trouvé");
			
			// si l'utilisateur n'est pas l'auteur de l'article
			} elseif ($article['authorId'] != $userSession->getId())
			{
				$flashbag->add("Vous n'etes pas autorisé à editer cette article");
			}
			$http->redirectTo('/admin/articles/');
		}

        $catModel = new CategoriesModel;
        $catList = $catModel->listAll();
        $selectedCat = $catModel->findByPost(intval($artId));
        $selectedCatId = array_column($selectedCat, 'id' );

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
                        'authorId'=> $article['authorId'],
                        'originalPicture' => $article['picture'],
                        'categories' => $selectedCatId
        ));
        $gateway = ['_form' => $form,
                    'catList' => $catList];
		
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
        
        if ($userSession->isAuthorized([1,2,3])==false)
            /** Redirection vers le dashboard */
            $http->redirectTo('/admin/');

		try
        {
            
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
            
            //categories
			$categories = $validator->formFilter($data['categories']);

            //verification d'unicité du titre 
            $articlesModel = new articlesModel();
			if ($articlesModel->findByNewTitle($data['title'],$data['id'])!=false)
                $validator->addError("le nouveau titre {$data['title']} est déjà existant");

            /**
             * image upload
             * @author ODRC
             */
            if ($http->hasUploadedFile('picture'))
            {
                $picture = new Upload($_FILES['picture']);
                /*@todo - bug dans la classe Upload qui ne charge pas la liste d'images supportées*/
                if ($picture->file_is_image)
                {
                    if ($picture->uploaded)
					{
						//taille original
						$uniqName = uniqid('post_');
						$picture->file_new_name_body = "bg_".$uniqName;
						$picture->file_overwrite = true;
						$picture->process(WWW_PATH."/assets/images/posts/");
						//taille medium
						$picture->file_new_name_body = "md_".$uniqName;
						$picture->image_resize =true;
						$picture->image_x = 1200;
						$picture->image_y = 600;
						$picture->file_overwrite = true;
						$picture->process(WWW_PATH."/assets/images/posts/");
						//taille small
						$picture->file_new_name_body = "sm_".$uniqName;
						$picture->image_resize =true;
						$picture->image_x = 600;
						$picture->image_ratio_y = true;
						$picture->file_overwrite = true;
						$picture->process(WWW_PATH."/assets/images/posts/");

						if ($picture->processed)
						{
							//nom pour la bdd
							$pictureNameBd = $uniqName.'.'.$picture->file_dst_name_ext;
							
						} else{
							$validator->addError($picture->error);
						}
					} else {
						$validator->addError($picture->error);
					}
                } else{
                    $validator->addError("ceci n'est pas une immage");
                    $pictureNameBd = $formFields['originalPicture'];
                }

            } else {
                $pictureNameBd = $formFields['originalPicture'];
            }
                
            if (empty($validator->getErrors())==false)
            {
                //supprimer les images uploadés
				if (file_exists(WWW_PATH."\assets\images\posts\bg_{$pictureNameBd}"))
                    unlink(WWW_PATH."\assets\images\posts\bg_{$pictureNameBd}");
                 if (file_exists(WWW_PATH."\assets\images\posts\md_{$pictureNameBd}"))
                    unlink(WWW_PATH."\assets\images\posts\md_{$pictureNameBd}");
                 if (file_exists(WWW_PATH."\assets\images\posts\sm_{$pictureNameBd}"))
                    unlink(WWW_PATH."\assets\images\posts\sm_{$pictureNameBd}");

                throw new DomainException("DExc - Erreur de validation des champs du formulaire");
            }
            
            //supprimer l'ancien image
            if ($formFields['originalPicture']!=NULL && file_exists(WWW_PATH."\assets\images\posts\bg_{$formFields['originalPicture']}"))
                unlink(WWW_PATH."\assets\images\posts\bg_{$formFields['originalPicture']}");
            if ($formFields['originalPicture']!=NULL && file_exists(WWW_PATH."\assets\images\posts\md_{$formFields['originalPicture']}"))
                unlink(WWW_PATH."\assets\images\posts\md_{$formFields['originalPicture']}");
            if ($formFields['originalPicture']!=NULL && file_exists(WWW_PATH."\assets\images\posts\sm_{$formFields['originalPicture']}"))
                unlink(WWW_PATH."\assets\images\posts\sm_{$formFields['originalPicture']}");


            /** Enregistrer les données dans la base de données */
            $articlesModel = new ArticlesModel();
            $lastArticle = $articlesModel->update($data['id'],
                                $data['title'], 
                                $data['metaTitle'],
                                $data['summary'],
                                $data['content'],
                                $data['published'],
                                $pictureNameBd
                                );
            
            //update categories
            if (isset($lastArticle))
			{
                $catModel = new CategoriesModel();
                $catModel->delHasRelation($lastArticle);
				$catModel->addCategories($lastArticle, $categories);
			}


            /** Ajout du flashbag */
            $flashbag->add('L\'article a bien été modifiée');
            
            /** Redirection vers la liste */
            $http->redirectTo('admin/articles/');
        }
         catch(Exception $exception)
        {

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