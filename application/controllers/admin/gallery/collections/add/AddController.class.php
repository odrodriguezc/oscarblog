<?php
/**
 * @class AddController
 * 
 * nous permet d'ajouter une image dans une collection
 */

class AddController
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
        }
		
		$http->redirectTo('/admin/gallery/');
		


		
		
		return [];
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
		$flashbag = new FlashBag();
		if ($userSession->isAuthenticated()==false) 
			/** Redirection vers le login */
			$http->redirectTo('/login/');
        
        if ($userSession->isAuthorized([1,2,3])==false)
		{
			$flashbag->add("Vous n'estes pas autorisé");
		}
		
		$validator = new DataValidation();
		$galleryModel = new galleryModel();
		
        $colId = $validator->inputFilter($formFields['collection']);
		$picId = $validator->inputFilter($formFields['picId']);

		$picCollections = $galleryModel->findCollectionsByPic(intval($picId));
		
		/**
		 * check si l'imange n'est dejá enregistré dans la collection donné
		 */
		$check=false;
		if (isset($picCollections) && count($picCollections)>0)
		{
			foreach ($picCollections as $key => $picCollection) 
			{
				if ($colId === $picCollection['id'])
				{
					$check = true;
				}
			}
		}
		
		if ($check) 
		{
			echo ("l'image selectionée est déjà presente dans la collection-0");

		} else{
			$galleryModel->addToCollections(intval($picId), [intval($colId)]);
			echo ("l'image a été ajouté à la collection-1");
		}
		exit();

		
    }
}