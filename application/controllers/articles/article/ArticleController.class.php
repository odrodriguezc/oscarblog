<?php

class ArticleController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
    	
		$ArticlesModel = new ArticlesModel();
        $flashbag = new FlashBag();
        
        if ( !array_key_exists('id', $queryFields) || $queryFields['id'] ==='')
        {   $flashbag->add('Un article doit etre indiqué');
            $http->redirectTo('/home/');
        }
        
        $validator = new DataValidation();
        $dataId = $validator->inputFilter($queryFields['id']);

        $article = $ArticlesModel->find($dataId);
        
        $gateway =  ['article' => $article,
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