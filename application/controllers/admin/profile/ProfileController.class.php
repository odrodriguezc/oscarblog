<?php

class ProfileController
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
        
		if ($userSession->isAuthorized([1,2,3])==false)
		{
			$flashbag->add("Vous n'estes pas autorisé");
			$http->redirectTo('/admin/');
		}
		

		/**
		 * usermodel
		 * @var UsersModel instance de la class UserModel
		 * @author ODRC 
		*/
		$usersModel = new UsersModel();
		$articlesModel = new ArticlesModel();
		$commentsModel = new CommentsModel();
		$galleryModel = new GalleryModel();
		//id de l'utilisateur en session
		$userId = $userSession->getId();

		/**
		 *  Recuperations des articles et des commentares liés à l'author 
		 * 
		 * @var array|false $articles liste d'articles crées par l'author | false s'il n'y en a pas
		 * @var array|false $comments liste de commentaries sur les articles crées par l'author | false s'il n'y en a pas
		 * @var array $recently tableau receptacle ou seron melangé les resultats des requetes precedantes
		 * @author ODRC
		 */
		$user = $usersModel->find($userId);
		$articles = $articlesModel->findByAuthor($userId, 5);
		$comments = $commentsModel->findByPostAuthor($userId, 5);
		$pics = $galleryModel->findByAuthor($userId, 5);

		/**
		 * dateFormat
		 * @param string $date
		 * @return string $formatedDate
		 * @author ODRC
		 */
		function dateFormat(string $date):string
		{
			$dateObj = date_create($date);
			return date_format($dateObj, 'g:ia \o\n l jS F Y');
		}


		/**
		 * calcTimepast
		 * 
		 * calcule le temps ecoulé en le tranchant en minutes, heures, jour ou semaines
		 * 
		 * @var string $timePast temps écoulé depuis la derniere update et le temps present (en minutes)
		 * @return string $timePastStr 
		 * @author ODRC
		 */
		function calcTimePast(string $timePast):string
		{
			$time = intval($timePast);
			if ($time < 60)
				$timePastStr = $time.' minutes';
			elseif ($time >= 60 && $time < 1440) 
				$timePastStr = number_format($time/60,0) .' heures';
			elseif ($time >= 1440  && $time < 10080 )
				$timePastStr = number_format($time/1440) .' jours';
			elseif ($time >= 10080)
				$timePastStr = number_format($time/10080) .' semaines';

			return $timePastStr;
		}


		/**
		  * 
		  * @var user array information correspondante à l'utilisateur recherché
		  * @var roles array liste de roles conus
		  * 
		*/
		$gateway = ['roles' => $usersModel->role,
					'user' => $user,
					'articles' => $articles,
					'comments' => $comments,
					'pics' => $pics
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