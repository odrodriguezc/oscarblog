<?php

class DelController
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
        else
        
        if ($userSession->isAuthorized([2,3])==false)
		/** Redirection vers le dashboard */
		$http->redirectTo('/login/');


        $catModel = new CategoriesModel();

		$catModel->delete($queryFields['id']);

		/**
		 * Flashbag et Redirectionnement
		 * 
		 */
		$flashbag = new Flashbag();
		$flashbag->add('La categorie a bien été supprimé');
		
		$http->redirectTo('admin/categories/');
	
		
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
        else
        
        if ($userSession->isAuthorized([2,3])==false)
		/** Redirection vers le dashboard */
		$http->redirectTo('/login/');


		
    }
}