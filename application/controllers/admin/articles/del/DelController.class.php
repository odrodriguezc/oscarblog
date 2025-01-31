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
		$flashbag = new FlashBag();
		if ($userSession->isAuthenticated()==false) 
			/** Redirection vers le login */
			$http->redirectTo('/login/');
		
		 /**
         * Si on accede sans especifier un querystring ou en le laisant vide on envoie une message en flashbag et on redirige vers l'admin
         */
        if ( !array_key_exists('id', $queryFields) || $queryFields['id']==='')
        {   $flashbag->add('Un article doit etre indiqué pour le modifer');
            $http->redirectTo('/admin/users/');
		}

		$articlesModel = new ArticlesModel();
		$validator = new DataValidation();
		$dataId = $validator->inputFilter($queryFields['id']);
		$article = $articlesModel->find($dataId);
		//Si l'utilisateur n'est pas autorisé
		if ($article==false)
		{
			$flashbag->add("L'article n'a pas été trouvé");
			$http->redirectTo('/admin/articles/');
		}

		if ($userSession->isAuthorized([3])==false)
		{		
			// si l'utilisateur n'est pas l'auteur de l'article
			if ($article['authorId'] != $userSession->getId())
			{
				$flashbag->add("Vous n'etes pas autorisé à supprimer cette article");
			}
			$http->redirectTo('/admin/articles/');
		}

		
		//supprimer l'ancien image
		if ($article['picture']!=NULL && file_exists(WWW_PATH."\assets\images\posts\bg_{$article['picture']}"))
			unlink(WWW_PATH."\assets\images\posts\bg_{$article['picture']}");
		if ($article['picture']!=NULL && file_exists(WWW_PATH."\assets\images\posts\md_{$article['picture']}"))
			unlink(WWW_PATH."\assets\images\posts\md_{$article['picture']}");
		if ($article['picture']!=NULL && file_exists(WWW_PATH."\assets\images\posts\sm_{$article['picture']}"))
			unlink(WWW_PATH."\assets\images\posts\sm_{$article['picture']}");

		/**
		 * @todo implementer la diferentiation lorsque la requete delete n'abouti pas
		 */
		$articlesModel->delete(intval($dataId));
	
		$flashbag->add('L\'article a bien été supprimé');


		$http->redirectTo('admin/articles/');
	
		
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