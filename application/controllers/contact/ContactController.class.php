<?php

class ContactController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP GET
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
    	 */
	
		$flashbag = new FlashBag();
		$userSession = new UserSession();
		$articlesModel = new ArticlesModel();

		$contacts =  $articlesModel->findByTitle('__Contacts__');

		$contacts['title'] = strtr($contacts['title'],'_', '');

		$gateway = ['flashbag' => $flashbag->fetchMessages(),
					'contact' => $contacts
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
		$flashbag = new FlashBag();
		$userSession = new UserSession();

		try {
			//code...
			//instance de la classe DataValidation
			$validator = new DataValidation;
			$contactModel = new ContactModel();
	
			//verifications des champs obligatoires
			$validator->obligatoryFields(['email' => $formFields['email'],
											'message' => $formFields['message']
											]);
	
			//securisation de la donné 
			$data = $validator->formFilter($formFields);
	
			//longueur des champs
			$validator->lengtOne($data['name'], 'name', 255,0);	
			$validator->lengtOne($data['message'], 'message', 50000);
			$validator->email($data['email']);
			
			if (empty($validator->getErrors())==false)
			{
				throw new DomainException("Error Processing Request", 1);
			}

			/** Enregistrer les données dans la base de données */

			$contactModel->addMessage($data['email'], $data['message'], $data['name']);

		} catch (DomainException $th) {
			//throw $th;
			/** Réaffichage du formulaire avec un message d'erreur. */
			$form = new ContactForm();
			/** On bind nos données $_POST ($formFields) avec notre objet formulaire */
			$form->bind($formFields);
			//liste d'erreur dans le formulair avec le message pour chaq'un
			$form->setErrorMessage($validator->getErrors());
			//erreur lancé dans l'exeption
			$form->addError($th->getMessage());
			


			return   ['_form' => $form];
		}


    }
}