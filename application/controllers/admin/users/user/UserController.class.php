<?php

class UserController
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
        
        if ($userSession->isAuthorized([2,3])==false)
            /** Redirection vers le referer */
            header("location: {$_SERVER['HTTP_REFERER']}");
		

		/**
		 * usermodel
		 * instance du model users et stackage dans une variable
		*/

		$userModel = new UsersModel();
		$flashbag = new FlashBag();

		 /**
		  * 
		  * @var user array information correspondante à l'utilisateur recherché
		  * @var roles array liste de roles conus
		  * 
		  */

		$gateway['roles'] = $userModel->role;
		$user=$userModel->find($queryFields['id']);
		//destruction de l'index password pour ne pas le passer à la vue
		unset($user['passwordHash']);
		$gateway['user']=$user;


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