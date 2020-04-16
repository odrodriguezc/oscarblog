<?php

class HomeController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP GET
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
    	 */
	
		
	
		$ArticlesModel = new ArticlesModel();
		$catModel = new CategoriesModel();
		$flashbag = new FlashBag();

		$articleList = $ArticlesModel->listPublishedAll(3);
		$catList = $catModel->listAll();




		$gateway = 	['articlesList' => $articleList,
					'catList' => $catList,
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