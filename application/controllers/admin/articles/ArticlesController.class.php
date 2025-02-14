<?php

class ArticlesController
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
		 * gateway 
		 * 
		 * variable tableau qui nous permet d'organiser le passage des variables à la vue
		 * 
		 * @var  array $articleList liste des articles
		 * @var array fetchMessages appel à la methode fechtMessages de lac la class Flashbag
		 */
		$ArticlesModel = new ArticlesModel();

		$gateway = ['articlesList' => $ArticlesModel->listAll(),
					'flashbag' => $flashbag->fetchMessages(),
					'pageTitle' => $http->getRequestFile()
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