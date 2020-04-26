<?php

use Email\Email;
use Email\SendEmail;

class ContactController
{
	public function httpGetMethod(Http $http, array $queryFields)
	{

		$flashbag = new FlashBag();
		$userSession = new UserSession();
		$articlesModel = new ArticlesModel();

		$contacts =  $articlesModel->findByTitle('_CONTACTS');


		$gateway = [
			'flashbag' => $flashbag->fetchMessages(),
			'contacts' => $contacts
		];


		return $gateway;
	}

	public function httpPostMethod(Http $http, array $formFields)
	{
		$flashbag = new FlashBag();
		$userSession = new UserSession();


		try {
			//instance de la classe DataValidation
			$validator = new DataValidation;
			$contactModel = new ContactModel();

			//verifications des champs obligatoires
			$validator->obligatoryFields([
				'email' => $formFields['email'],
				'message' => $formFields['message']
			]);

			//securisation de la donné 
			$data = $validator->formFilter($formFields);

			//longueur des champs
			$validator->lengtOne($data['name'], 'name', 255, 0);
			$validator->lengtOne($data['message'], 'message', 50000);
			$validator->email($data['email']);

			if (empty($validator->getErrors()) == false) {
				throw new DomainException("Error Processing Request", 1);
			}

			/** Enregistrer les données dans la base de données */
			$contactModel->addMessage($data['email'], $data['message'], $data['name']);

			//generer email automatique pour demander la confimation de l'adhesion
			$email = new Email($data['email'], 'contact.oscarblog@gmail.com', "Confirmation de reception de votre message", "<p>fkaldfadlñ</p><p>kfadfñkadkfajdkfajdñfkajdñfakdjfñakdjfñkajd dkjfak fka dfkadj fkadjfñadkfjañdkfadjñfkadj f</p>");

			$mailler = new SendEmail();
			$response = $mailler->process($email);

			///envoyer link pour creer le profil


			$http->redirectTo('/');
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

			$articlesModel = new ArticlesModel();
			$contacts =  $articlesModel->findByTitle('_CONTACTS');

			return   [
				'_form' => $form,
				'flashbag' => $flashbag->fetchMessages(),
				'contacts' => $contacts
			];
		}
	}
}
