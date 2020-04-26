<?php

class UserController
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
		if ($userSession->isAuthenticated() == false)
			$http->redirectTo('/login/');

		if ($userSession->isAuthorized([2, 3]) == false) {
			$flashbag->add("Vous n'estes pas autorisé");
			$http->redirectTo('/admin/');
		}

		/**
		 * Si on accede sans especifier un querystring ou en le laisant vide on envoie une message en flashbag et on redirige vers l'admin
		 */
		if (!array_key_exists('id', $queryFields) || $queryFields['id'] === '') {
			$flashbag->add('Un utilisateur doit etre indiqué');
			$http->redirectTo('/admin/');
		}




		/**
		 * @var UsersModel $userModel instance du model users et stackage dans une variable
		 * @var DataValidation $validator instance de l'objet DataValidation
		 */
		$userModel = new UsersModel();
		$validator = new DataValidation();
		$dataId = $validator->inputFilter($queryFields['id']);

		$user = $userModel->findById(intval($dataId));

		if ($user == false) {
			$flashbag->add("L'utilisateur recherché n'existe pas en base de donné");
			$http->redirectTo('/admin/users/');
		}

		//destruction de l'index password pour ne pas le passer à la vue
		unset($user['passwordHash']);

		$gateway = [
			'user' => $user,
			'roles' => $userModel->role,
			'pageTitle' => $http->getRequestFile()
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
