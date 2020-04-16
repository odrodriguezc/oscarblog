<?php

class ActionController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP GET
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
    	 */
	
		

		
		
		
		

		
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

		$ArticlesModel = new ArticlesModel();
		$validator = new DataValidation();

		//AJAX 
		$input = file_get_contents("php://input");
        if ($input != false) 
        {
			$data = $validator->formFilter(json_decode($input, $assoc = true));
			
			if (is_numeric($data['id']) && ($data['action'] === 'likes'|| $data['action'] === 'dislikes' || $data['action'] === 'share'))
			{
				$ArticlesModel->setAction(intval($data['id']), $data['action']);
				$article = $ArticlesModel->find(intval($data['id']));
				
				$http->sendJsonResponse($article);
			}
		} else {
			$http->sendJsonResponse("Une erreur est survenue lors du traitement de la requette");
		}

		
    }
}