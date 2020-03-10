<?php

class UserSession
	{
		final public function __construct()
		{
			if(session_status() == PHP_SESSION_NONE)
			{
				// Démarrage du module PHP de gestion des sessions.
				session_start();
			}
		}

		public function create($id, $username, $firstname, $lastname, $email, $role, $status, $avatar)
		{
			// Construction de la session utilisateur.
			$_SESSION['user'] = array(
								'id' => $id,
								'username' => $username,
								'firstname' => $firstname,
								'lastname' => $lastname,
								'email' => $email,
								'role'=> $role,
								'status'=> $status,
								'avatar' => $avatar,
								'connected' => true);

		}
	
		final public function destroy()
		{
			// Destruction de l'ensemble de la session.
			$_SESSION = array();
			session_destroy();
		}

		final public function createPost($postId)
		{
			// Construction de la session utilisateur.
			$_SESSION['post'] = $postId;
		}


		final public function getPostId()
		{
			if($this->isAuthenticated() == false || !isset($_SESSION['post']))
			{
				return null;
			}

			return $_SESSION['post'];
		}

		public function getUsername(){

			if($this->isAuthenticated() == false || !isset($_SESSION['user']))
			{
				return null;
			}

			return $_SESSION['user']['username'];
		}

		public function getEmail(){

			if($this->isAuthenticated() == false || !isset($_SESSION['user']))
			{
				return null;
			}

			return $_SESSION['user']['email'];
		}

		public function getfirstname(){
			
			if($this->isAuthenticated() == false || !isset($_SESSION['user']))
			{
				return null;
			}

			return $_SESSION['user']['firstname'];
		}
		
		public function getFullName(){
			
			if($this->isAuthenticated() == false || !isset($_SESSION['user']))
			{
				return null;
			}
			return $this->getfirstname().$this->getlastname();
		}

		public function getlastname(){

			if($this->isAuthenticated() == false || !isset($_SESSION['user']))
			{
				return null;
			}

			return $_SESSION['user']['lastname'];
		}

		public function getId(){

			if($this->isAuthenticated() == false || !isset($_SESSION['user']))
			{
				return null;
			}

			return $_SESSION['user']['id'];
		}

		public function getRole(){

			if($this->isAuthenticated() == false || !isset($_SESSION['user']))
			{
				return null;
			}

			return $_SESSION['user']['role'];
		}

		public function getAvatar(){

			if($this->isAuthenticated() == false || !isset($_SESSION['user']))
			{
				return null;
			}

			return $_SESSION['user']['avatar'];
		}

		public function getStatus(){

			if($this->isAuthenticated() == false || !isset($_SESSION['user']))
			{
				return null;
			}

			return $_SESSION['user']['status'];
		}

		/**
		 * isAuthenticated - Determine si l'itilisateur est connecté
		 */
		public function isAuthenticated(){

			return (isset($_SESSION['user']['connected'])) ? true : false;
		}

		/**
		 * isAuthorized - Determine si le role de l'utilisateur lui permet d'acceder à une fonctionalité donnée
		 * 
		 * @var array $authorizedRoles
		 * @return bool true si l'utilisateur est authorisé ou false s'il ne l'ai pas
		 */
		public function isAuthorized(array $authorizedRoles){
			return in_array(intval($_SESSION['user']['role']),$authorizedRoles,$strict = true);
		}


	}