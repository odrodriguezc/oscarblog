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

		if ($userSession->isAuthorized([3])==false)
		{
			//on determine si le user à supprimer est different à celui du user connecté
			if ($userSession->getId() != $queryFields['id'])
			{
				$flashbag->add('Vous n\'etes pas autorisé à suprimer cet utilisateur');
				$http->redirectTo('/admin/');
			}
		}

		 /**
         * Si on accede sans especifier un querystring ou en le laisant vide on envoie une message en flashbag et on redirige vers l'admin
         */
        if ( !array_key_exists('id', $queryFields) || $queryFields['id']==='')
        {   $flashbag->add('Un utilisateur doit etre indiqué pour le modifer');
            $http->redirectTo('/admin/users/');
		}

		$validator = new DataValidation();
		$dataId = $validator->inputFilter($queryFields['id']);

		/**
		 * @var Usermodel $usermodel
		 *
		 *  instance du model users et stackage dans une variable
		 */
		$userModel = new UsersModel();
		
		if ( $userModel->delete(intval($dataId)== false)
		{
			$flashbag->add("l'utilisateur n'a pas pu etre supprimé");
			/** Suppression de la photo de profil de l'utilisateur */
			/*$picture = $productModel->find($id);
			$image = $picture['prod_picture'];
			if($image != NULL && file_exists(WWW_PATH.'/uploads/products/'.$image)){
				unlink(WWW_PATH.'/uploads/products/'.$image);
			}*/
		} else {
			$flashbag->add("L'utilisateur a bien été supprimé");
		}
		
		$http->redirectTo('/admin/users/');
	
		
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