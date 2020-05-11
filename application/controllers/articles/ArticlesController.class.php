<?php

use Faker\Factory;

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

		$ArticlesModel = new ArticlesModel();
		$flashbag = new FlashBag();

		$gateway = [
			'articlesList' => $ArticlesModel->listPublishedAll(),
			'presentation' => $ArticlesModel->findByTitle('_PRESENTATION'),
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
