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
		else
		
		try
		{
			 /** Image uploadée
            *   On la déplace sinon on affecte à NULL pour la saisie en base
            */
            if ($http->hasUploadedFile('picture'))
                $picture = $http->moveUploadedFile('picture','/assets/images/articles/');
            else 
				$picture = NULL;
			
			  /** On vérifie que tous les champs sont remplis sauf */
			  foreach($formFields as $index=>$formField)
			  {
				  if (empty($formField) && ($index != 'metaTitle' || $index != 'picture' ))
					  throw new DomainException('manque'.$index.'' );
			  }
  
			  $authorId = $userSession->getId();

			  /** Enregistrer les données dans la base de données */
			  $ArticlesModel = new ArticlesModel();
			  $ArticlesModel->add($formFields['title'], 
								$formFields['metaTitle'],
								$formFields['summary'],
								$formFields['content'],
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
			 $form->setErrorMessage($exception->getMessage());
			
			 $ArticlesModel = new ArticlesModel();
			 return   ['_form' => $form];

		}
    }
}