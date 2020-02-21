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

		public function create($userId, $firstName, $lastName, $email)
		{
			// Construction de la session utilisateur.
			$_SESSION['user'] = array(
								'id' => $userId,
								'firstname' => $firstName,
								'lastname' => $lastName,
								'email' => $email,
								'connected' => true);

		}
	
		final public function destroy()
		{
			// Destruction de l'ensemble de la session.
			$_SESSION = array();
			session_destroy();
		}

		final public function createOrder($orderId)
		{
			// Construction de la session utilisateur.
			$_SESSION['order'] = $orderId;
		}



		final public function getOrderId()
		{
			if($this->isAuthenticated() == false || !isset($_SESSION['order']))
			{
				return null;
			}

			return $_SESSION['order'];
		}

		public function getEmail(){}

		public function getFirstName(){
			
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
			return $this->getFirstName().$this->getLastName();
		}

		public function getLastName(){

			if($this->isAuthenticated() == false || !isset($_SESSION['user']))
			{
				return null;
			}

			return $_SESSION['user']['lastName'];
		}

		public function getUserId(){

			if($this->isAuthenticated() == false || !isset($_SESSION['user']))
			{
				return null;
			}

			return $_SESSION['user']['id'];
		}

		public function isAuthenticated(){

			return (isset($_SESSION['user']['connected'])) ? true : false;
		}


	}