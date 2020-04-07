<?php

class ImageController
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

		 /**
         * Si on accede sans especifier un querystring ou en le laisant vide on envoie une message en flashbag et on redirige vers l'admin
         */
        if ( !array_key_exists('id', $queryFields) || $queryFields['id']==='')
        {   $flashbag->add('Une image  doit etre indiqué');
            $http->redirectTo('/admin/gallery/');
        }
		
        $galleryModel = new GalleryModel();
        $form = new PictureForm();
		$validator = new DataValidation();
		$dataId = $validator->inputFilter($queryFields['id']);

        $pic = $galleryModel->find(intval($dataId));
        $collectionList = $galleryModel->findCollections(intval($userSession->getId()));
        $inCollection = [];
        $form->bind(array('id' => $pic['id'],
                        'label' => $pic['label'],
                        'description' => $pic['description'],
                        'uniqueName' => $pic['uniqueName'],
                        'published' => $pic['published'],
                        'posted' => $pic['posted'],
                        'metadata' => $pic['metadata'],
                        'like' => $pic['like'],
                        'dislike' => $pic['dislike'],
                        'share' => $pic['share'],
                        'userId' => $pic['userId'],
                        'uploadAt' => $pic['uploadAt'],
                        'collections' => $inCollection
                        ));
		

		$gateway = [ '_form' => $form, 'collectionList' => $collectionList];
	
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
            $validator->obligatoryFields(['label' => $formFields['label']]);
              //securisation de la donné 
            $data = $validator->formFilter($formFields);
            
            //longueur des champs
            $validator->lengtOne($data['label'], 'Nom', 150);
            $validator->lengtOne($data['description'], 'Description', 500);
            
            //collections
            $collections = $validator->formFilter($data['collections']);
            
            if (!empty($validator->getErrors()))
            {
                throw new DomainException("Form: Error Processing Request", 1);
            }

            $galleryModel = new GalleryModel();
            $lastPic = $galleryModel->update($data['id'],
                                     $data['label'],
                                    $data['description']
                                    );
            
            //update collections
            if (isset($lastPic))
			{
                /*$catModel = new CategoriesModel();
                $catModel->delHasRelation($lastArticle);
				$catModel->addCategories($lastArticle, $categories);*/
            }
            
             /** Ajout du flashbag */
             $flashbag->add('L\'image a bien été modifiée');
            
             /** Redirection vers la liste */
             $http->redirectTo('admin/gallery/');
        
            
        } catch (DomainException $exception) {
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