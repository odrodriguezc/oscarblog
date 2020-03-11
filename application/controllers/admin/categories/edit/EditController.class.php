<?php

class EditController
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

        $i = 0;
        do {  
            if ($catList[$i]['id'] === $queryFields['id']){
                $category = $catList[$i];
            }
            $i++;
        } while ($i <= count($catList) && empty($category));

        $form = new CategoriesForm();
        $form->bind(array('id'=> $category['id'],
                        'title'=>$category['title'],
                        'description'=>$category['description'],
                        'parentId'=>$category['parentId']
        ));


		 /**
		  * 
		  * @var _form Array contenat les variables fournis par la class UsersForm
		  */
        $gateway = ['_form' => $form,
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

		if ($userSession->isAuthorized([2,3])==false)
		/** Redirection vers le dashboard */
		$http->redirectTo('/login/');

		
		$catModel = new CategoriesModel();
		$catList = $catModel->listAll();

		try
		{
                
            /** On vérifie que tous les champs sont remplis sauf */
			if (empty($formFields['title']))
				throw new DomainException('Il faut remplir le champs title' );
	
            /** Bindage du champ parentId avec la possibilite de le passer en NULL */
            $parentId = ($formFields['parentId'] === 'NULL') ? $parentId = NULL : $parentId = $formFields['parentId']; ;


            /** Enregistrer les données dans la base de données */
            $catModel->update($formFields['title'],
                            $formFields['description'], 
                            $parentId,
                            $formFields['id']
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