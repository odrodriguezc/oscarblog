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
		
		if ($userSession->isAuthorized([1,2,3])==false)
		/** Redirection vers le dashboard */
			$http->redirectTo('/login/');


		return ['_form' => new ArticlesForm()];; 
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
		if ($userSession->isAuthorized([1,2,3])==false)
		/** Redirection vers le dashboard */
			$http->redirectTo('/login/');
		
		try
		{
			/** Image uploadée
            *   On la déplace sinon on affecte à l'image par defaut pour la saisie en base
            */
            if ($http->hasUploadedFile('picture'))
                $picture = $http->moveUploadedFile('picture','/assets/images/articles/');
            else 
				$picture = 'default_picture.jpg';
			
			//instance de la classe DataValidation
			$validator = new DataValidation;

			//verifications des champs obligatoires
			$validator->obligatoryFields(['title' => $formFields['title'],
											'summary' => $formFields['summary'],
											'content' => $formFields['content']
											]);

			//securisation de la donné 
			$data = $validator->formFilter($formFields);

			//longueur des champs
			$validator->lengtOne($data['title'], 'Titre', 255);	
			$validator->lengtOne($data['metaTitle'], 'Soustitre', 255);
			$validator->lengtOne($data['summary'], 'Resumé', 500);
			$validator->lengtOne($data['content'], 'Contenu', 50000);

			//verification d'unicité du titre 
			$articlesModel = new articlesModel();
			if ($articlesModel->findByTitle($data['title'])!=false)
				$validator->addError("le titre {$data['title']} est déjà existant");

			if (empty($validator->getErrors())==false)
				throw new DomainException("DExc - Erreur de validation des champs du formulaire");
			
			$authorId = $userSession->getId();

			/** Enregistrer les données dans la base de données */
			$articlesModel->add($data['title'], 
								$data['metaTitle'],
								$data['summary'],
								$data['content'],
								$picture,
								$authorId
								);
			  
			/** Ajout du flashbag */
			$flashbag = new Flashbag();
			$flashbag->add('L\'article a bien été ajouté');
			
			/** Redirection vers la liste */
			$http->redirectTo('admin/articles/');


		}
		catch(DomainException $exception)
		{
		/** DomainException est un type d'exception prédéfinie par PHP (valeur en dehors des limites selon la doc, on l'utilise donc ici pour ça !)
		 *   On a choisi ce type d'exception dans l'arbre généalogique des exceptions fournies par PHP. On aurait pu faire notre propre class
		 *   Exemple : class FormValideException extends Exception {}
		 */
		
			/** Réaffichage du formulaire avec un message d'erreur. */
			$form = new ArticlesForm();
			/** On bind nos données $_POST ($formFields) avec notre objet formulaire */
			$form->bind($formFields);
			//liste d'erreur dans le formulair avec le message pour chaq'un
			$form->setErrorMessage($validator->getErrors());
			//erreur lancé dans l'exeption
			$form->addError($exception->getMessage());
			

			return   ['_form' => $form];

		}
    }
}