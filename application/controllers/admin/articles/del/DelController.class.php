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

		$userModel = new UsersModel();
		$validator = new DataValidation();
		$dataId = $validator->inputFilter($queryFields['id']);
		
		//Si l'utilisateur n'est pas autorisé
		if ($userSession->isAuthorized([3])==false)
		{
			//recherche de l'article à supprimer
			$article = $userModel->find($dataId);
			if (!$article)
			{
				$flashbag->add("L'article n'a pas été trouvé");
			// si l'utilisateur n'est pas l'auteur de l'article
			} elseif ($article['author_id'] != $userSession->getId())
			{
				$flashbag->add("Vous n'etes pas autorisé à supprimer cette article");
			}

			$http->redirectTo('/admin/articles/');
		}

		
		if ($userModel->delete(intval($dataId)) ==false)
		{
			$flashbag->add("L'article n'a pas été trouvé");
		} else {
			$flashbag->add('L\'article a bien été supprimé');
			//supression de la photo ou pas?
		}

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