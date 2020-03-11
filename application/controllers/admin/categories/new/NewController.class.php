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
		if ($userSession->isAuthenticated()==false) 
			/** Redirection vers le login */
			$http->redirectTo('/login/');
		
		if ($userSession->isAuthorized([2,3])==false)
		/** Redirection vers le dashboard */
		$http->redirectTo('/login/');

        /** Instance du model pour recuperer la liste de categories et les aficher dans la vu */
        $catModel = new CategoriesModel();
        $catList = $catModel->listAll();

		 /**
		  * 
		  * @var _form Array contenat les variables fournis par la class UsersForm
		  */
        $gateway = ['_form' => new CategoriesForm(),
                    'catList' => $catList
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
		else
		
		$catModel = new CategoriesModel();
		$catList = $catModel->listAll();

		try
		{
                
            /** On vérifie que tous les champs sont remplis sauf */
		
			if (empty($formFields['title']))
				throw new DomainException('Il faut remplir le champs title' );

			/** Verifier si la categorie existe déjà en BD */
			foreach ($catList as $key => $catValue) {
				if ($formFields['title']===$catValue['title'])
				throw new DomainException('La categorie est deja existante');
			}
			
			if ($formFields['parentId'] === 'NULL')
				$parentId = NULL;

            /** Enregistrer les données dans la base de données */
            $catModel->add($formFields['title'],
                            $formFields['description'], 
                            $parentId
                            );
            
            /** Ajout du flashbag */
            $flashbag = new Flashbag();
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
			$form->setErrorMessage($exception->getMessage());
			
			$usersModel = new UsersModel();
			return   ['_form' => $form,
					'catList' => $catList
			];

		}
    }
}