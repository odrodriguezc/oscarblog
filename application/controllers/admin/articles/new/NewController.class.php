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
		
		if ($userSession->isAuthorized([1,2,3])==false)
		{
			$flashbag->add("Vous n'estes pas autorisé");
			$http->redirectTo('/admin/');
		}

		$catModel = new CategoriesModel;
		$catList = $catModel->listAll();

		return ['_form' => new ArticlesForm(),
				'catList' => $catList
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
		{
			$flashbag->add("Vous n'estes pas autorisé");
			$http->redirectTo('/admin/');
		}
		
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
			if ($articlesModel->findByTitle($data['title'])!=false)
				$validator->addError("le titre {$data['title']} est déjà existant");

			/**
			 * image upload
			 * 
			 * - Upload l'image et cree deux copies en taille medium et small
			 * @author ODRC
			 */
			$pictureNameBd = '';
			if (isset($_FILES['picture']) && $_FILES['picture']['error']==0)
			{
				$picture = new Upload($_FILES['picture']);
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
					$pictureNameBd = 'NULL';
				}
			} else {
				$pictureNameBd = 'NULL';
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
			
			$authorId = $userSession->getId();

			/** Enregistrer les données dans la base de données */
			$lastArticle = $articlesModel->add($data['title'], 
										$data['metaTitle'],
										$data['summary'],
										$data['content'],
										$data['published'],
										$pictureNameBd,
										$authorId
										);
			/** enregistrement des categories dans la bdd */
			if (isset($lastArticle))
			{
				$catModel = new CategoriesModel();
				$catModel->addCategories($lastArticle, $categories);
			}


			/** Ajout du flashbag */
			$flashbag->add('L\'article a bien été ajouté');
			
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
			
			$catModel = new CategoriesModel;
			$catList = $catModel->listAll();

			return   ['_form' => $form, 'catList' => $catList];

		}
    }
}