<?php

class GalleryController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP GET
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
    	 */
		
		$userSession = new UserSession();
		$flashbag = new FlashBag();
		$picModel = new GalleryModel();
		$picsByCollection = $picModel->listByPublicCollectionAll();
		$sortPicsByCollection=[];
		foreach ($picsByCollection as $key => $pic) 
		{
			if (!array_key_exists($pic['collectionId'],$sortPicsByCollection)) 
			{
				$sortPicsByCollection[$pic['collectionId']][] = $pic;
			} else{
				$sortPicsByCollection[$pic['collectionId']][] = $pic;
			}
		}
		unset($picsByCollection);


		$gateway = ['collections' => $sortPicsByCollection,
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