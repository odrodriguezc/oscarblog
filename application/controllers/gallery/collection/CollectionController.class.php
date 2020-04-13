<?php

class CollectionController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
		
		$userSession = new UserSession();
        $flashbag = new FlashBag();
        
        /**
         * Si on accede sans especifier un querystring ou en le laisant vide on envoie une message en flashbag et on redirige vers l'admin
         */
        if ( !array_key_exists('id', $queryFields) || $queryFields['id']==='')
        {   $flashbag->add('Une collection doit etre indiqué');
            $http->redirectTo('/admin/');
        }
        
        $validator = new DataValidation();
        $dataId = $validator->inputFilter($queryFields['id']);
        $picModel = new GalleryModel();
        
		$collection = $picModel->listPicByCollectionOne($dataId);
		


		$gateway = ['collection' => $collection,
					'flashbag' => $flashbag->fetchMessages()
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

		
    }
}