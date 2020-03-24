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
		$flashbag = new FlashBag();
		if ($userSession->isAuthenticated()==false) 
			$http->redirectTo('/login/');

		if ($userSession->isAuthorized([2,3])==false)
		{
			$flashbag->add("Vous n'estes pas autorisé");
			$http->redirectTo('/admin/');
		}

		$userModel = new UsersModel();
		/**
		 * gateway 
		 * 
		 * variable tableau qui nous permet d'organiser le passage des variables à la vue
		 * 
		 * @var  array liste des utilisateurs
		 * @var  array liste des roles conus
		 * @var array messages du flashbag
		 */
		$gateway = ['usersList' => $userModel->listAll(),
					'roles' => $userModel->role,
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