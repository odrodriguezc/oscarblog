<?php

class NewController
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

        /** Instance du model pour recuperer la liste de categories et les aficher dans la vu */
        $catModel = new CategoriesModel();
        $catList = $catModel->listAll();

		 /**
		  * 
		  * @var _form Array contenat les variables fournis par la class UsersForm
		  */
        $gateway = ['_form' => new CategoriesForm(),
					'catList' => $catList,
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

		/** 
		  * UserSession - instance de la classe session
		  * 
		  * - isAutheticated va nous permettre de savoir si l'utilisateur est connecté 
		  */
		$userSession = new UserSession();
		if ($userSession->isAuthenticated()==false) 
		/** Redirection vers le login */
		$http->redirectTo('/login/');
		  
		$flashbag = new Flashbag();
		if ($userSession->isAuthorized([1,2,3])==false)
		{
			$flashbag->add("Vous n'estes pas autorisé");
			$http->redirectTo('/admin/');
		}
		
		$catModel = new CategoriesModel();
		$catList = $catModel->listAll();

		try
		{
                
			$validator = new DataValidation();
			
			$validator->obligatoryFields(['Title' => $formFields['title']]);
			//securisation de la donné 
			$data = $validator->formFilter($formFields);
			$validator->lengtOne($data['title'], 'Titre', 255);
			$validator->lengtOne($data['description'],'Description',1000,0);

			/** 
			 * Verifier si la categorie existe déjà en BD 
			 * - Boucle sur la liste de categories dont on dispose, donc pas besoin d'une requete, pour verifier que le nouveau titre n'est déjà enregistré
			 * @author ODRC
			 * */
			foreach ($catList as $key => $catValue) 
			{
				if ($data['title'] === $catValue['title'] && $data['parentId'] === $catValue['parentId'])
					$validator->addError('La categorie est deja existante');
			}
				
			
			/**
			 * - afectattion de la variable $parentId avec l'eventuel null à passer en BDD ou la valeur saisi
			 * - control de la valeur lorsqu'elle n'est pas null pour determiner si c'est un parent existant
			 * 
			 * @author ODRC
			*/
			$parentId = $data['parentId'] === 'NULL' ? null : $data['parentId'];
			
			if ($parentId != null)
				if (in_array($parentId, array_column($catList, 'id'))==false)
					$validator->addError('La categorie parent n\'a pas été trouvé. Choisir parmi les existantes ou selectionez NULL pourfaire une categorie mere');
			
			if (empty($validator->getErrors())==false)
				throw new DomainException("DExc - Erreur de validation des champs du formulaire");

            /** Enregistrer les données dans la base de données */
            $catModel->add($data['title'],
                            $data['description'], 
                            $parentId
                            );
            
            /** Ajout du flashbag */
            $flashbag->add('La categorie a bien été ajouté');
            
            /** Redirection vers la liste */
            $http->redirectTo('admin/categories/');


		}
		catch(DomainException $exception)
		{
		/** DomainException est un type d'exception prédéfinie par PHP (valeur en dehors des limites selon la doc, on l'utilise donc ici pour ça !)
		 *   On a choisi ce type d'exception dans l'arbre généalogique des exceptions fournies par PHP. On aurait pu faire notre propre class
		 *   Exemple : class FormValideException extends Exception {}
		 */
		
			/** Réaffichage du formulaire avec un message d'erreur. */
			$form = new CategoriesForm();
			/** On bind nos données $_POST ($formFields) avec notre objet formulaire */
			$form->bind($formFields);
			//liste d'erreur dans le formulair avec le message pour chaq'un
			$form->setErrorMessage($validator->getErrors());
			//erreur lancé dans l'exeption
			$form->addError($exception->getMessage());
			
			//$usersModel = new UsersModel();
			return   ['_form' => $form,
					'catList' => $catList
					];

		}
    }
}