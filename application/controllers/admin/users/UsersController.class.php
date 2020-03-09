<?php

class UsersController
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
		  * - isAuthorized va nous permettre de determiner si le rol de l'utilisateur lui permet d'acceder à la function
		*/
		$userSession = new UserSession();
		if ($userSession->isAuthenticated()==false) 
			/** Redirection vers le login */
			$http->redirectTo('/login/');

		if ($userSession->isAuthorized([2,3])==false)
			/** Redirection vers le dashboard */
			$http->redirectTo('/login/');

		/**
		 * usermodel
		 * instance du model users et stackage dans une variable
		 */
		 $userModel = new UsersModel();

		 /**
		  * 
		  * @var userList array liste des utilisateurs
		  * @var roles array liste des roles conus
		  * 
		  */
		$gateway['usersList'] = $userModel->listAll();
		$gateway['roles'] = $userModel->role;

		
		
		
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