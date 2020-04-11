<?php
/**
 * @class PopController
 * 
 * nous permet de supprimer une image dans une collection
 */

class PopController
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
		{
			$http->redirectTo('/login/');
		}

        $http->redirectTo("/admin/gallery/");
        

		
	
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

		if (intval($colId) && intval($picId))
		{
			$galleryModel->popOffPic(intval($picId), intval($colId));
			echo ("La photo a été supprimée de la collection - 1");
		} else{
			echo ("La photo n'a pas été supprimée de la collection - 0");
		}
		
		exit();
		
    }
}