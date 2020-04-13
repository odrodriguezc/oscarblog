<?php
/**
 * @class newController
 * @todo gestion de l'affichage des erreurs envoyes en json lorsque les champs duformulares ne son pas validés
 * 
 * nous permet de creer une nouvelle collection
 */

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
        }
		
		$http->redirectTo('/admin/gallery/');
		


		
		
		return [];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP POST
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
		*/
		$userSession = new UserSession();
		$flashbag = new FlashBag();
        if ($userSession->isAuthenticated()==false) 
        {
			$http->redirectTo('/login/');
        }
        
        if ($userSession->isAuthorized([1,2,3])==false)
		{
			$flashbag->add("Vous n'estes pas autorisé");
		}
		
		$validator = new DataValidation();
        $galleryModel = new galleryModel();
        $userId = $userSession->getId();
        
        //AJAX 
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
        {
            // Traitement pour une requête AJAX
            if (empty($formFields['json']))
            {
                $validator->addError("La requette doit contenir au moins le titre de la collection");
            }

            $data = $validator->formFilter(json_decode($formFields['json'], $assoc = true));
            
        } else {
            //CLASIC
            $validator->obligatoryFields(['title', 'Titre', $formFields['title']]);
            
            //securisation de la donné 
			$data = $validator->formFilter($formFields);
        }
        
        //longueur des champs
        $validator->lengtOne($data['title'], 'Titre', 255); 
        $validator->lengtOne($data['description'], 'Description', 1000, 0);
        //integer boolean ( 0 ou 1)
        $validator->intBool($data['published']);
        
        if ($galleryModel->findColByTitle($data['title'])!=false)
        {
            $validator->addError("la collection {$data['title']} est déjà existante");
        }
        
        if (!empty($validator->getErrors()))
        {
            $http->sendJsonResponse($validator->getErrors());
        }

        /** Enregistrer les données dans la base de données */
        $lastRow = $galleryModel->newCollection(intval($userId),
                                                $data['title'],
                                                $data['description'],
                                                intval($data['published']));

        if (is_numeric($lastRow))
        {
            $lastCol = $galleryModel->findColByTitle($data['title']);
            $http->sendJsonResponse(['succes' => $lastCol]);
        } else{
            $http->sendJsonResponse(['error'=> "Erreur dans l'enregistrement en basse de donné" ]);
        }

    }
}

