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
		
		$userId = $userSession->getId();
		$picModel = new GalleryModel();
		$picList = $picModel->listAll();

		/**
		 * gateway 
		 * 
		 * variable tableau qui nous permet d'organiser le passage des variables à la vue
		 * 
		 * @var  array $picList 
		 * @var array flashbag appel à la methode fechtMessages de lac la class Flashbag
		 */
		$gateway = ['picList' => $picList,
					'flashbag' => $flashbag->fetchMessages()
					];

		return $gateway;
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    			
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


		try {
			var_dump($_FILES);
		} catch (DomainException $exeption) {
			//throw $th;
		}
		 
		
    }
}