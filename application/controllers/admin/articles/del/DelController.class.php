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
		if ($userSession->isAuthenticated()==false) 
			/** Redirection vers le login */
			$http->redirectTo('/login/');
		else

		/**
		 * usermodel
		 * instance du model users et stackage dans une variable
		 */
		$userModel = new UsersModel();
		
		/** Suppression de la photo de profil de l'utilisateur */
        /*$picture = $productModel->find($id);
        $image = $picture['prod_picture'];
        if($image != NULL && file_exists(WWW_PATH.'/uploads/products/'.$image)){
            unlink(WWW_PATH.'/uploads/products/'.$image);
        }*/

		$userModel->delete($queryFields['id']);

		/**
		 * Flashbag et Redirectionnement
		 * 
		 */
		$flashbag = new Flashbag();
		$flashbag->add('L\'utilisateur a bien été supprimé');
		
		$http->redirectTo('admin/users/');
	
		
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP POST
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
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
		else

		
    }
}