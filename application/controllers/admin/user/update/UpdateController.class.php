<?php

class UpdateController
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
		 * usermodel
		 * instance du model users et stackage dans une variable
		 */

		 $userModel = new UsersModel();

		/**
		 * @var user array whit information of a particular user
		 * @var roles array with the list of users roles 
		 * 
		 */
		$tab['user'] = $userModel->find($queryFields['id']);
		$tab['roles'] = $userModel->role;
	
		
		return $tab;
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