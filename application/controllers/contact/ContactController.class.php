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

		if (isset($queryFields['email'])) {

			$validator = new DataValidation();
			$email = $validator->email($queryFields['email']);
			if (!$email) {
				die("Email non conforme.");
			}
			$contactModel = new ContactModel();
			$contactModel->addSubscriber($validator->inputFilter($queryFields['email']));
			die("Votre addresse email a été enregistré");
		}

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
			if (!empty($data['subscribe']) && $data['subscribe'] === 'on') {
				$token = bin2hex(random_bytes(12));
				$tokenModel = new TokensModel();
				$tokenModel->generate($token, $data['email']);
				$email = new Email(
					$data['email'],
					'contact.oscarblog@gmail.com',
					"Merci de nous contacter - Creer votre profil",
					"<p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Non, dolorum velit libero sequi illum maxime?</p>
					<p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Veritatis inventore ex recusandae perspiciatis! Obcaecati, inventore nemo neque officiis aperiam fugit sint dolores ad, repudiandae sunt officia repellendus itaque deleniti velit nihil hic numquam nisi perspiciatis illum tempore? Ipsam, quisquam officiis!</p>
					<br><br><br>
					<a href=\"www.oscarblog.com/register/?token={$token}\">Ceer votre profil</a>"
				);
			} else {
				$email = new Email(
					$data['email'],
					'contact.oscarblog@gmail.com',
					"Merci de nous contacter",
					"<p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Non, dolorum velit libero sequi illum maxime?</p>
				<p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Veritatis inventore ex recusandae perspiciatis! Obcaecati, inventore nemo neque officiis aperiam fugit sint dolores ad, repudiandae sunt officia repellendus itaque deleniti velit nihil hic numquam nisi perspiciatis illum tempore? Ipsam, quisquam officiis!</p>"
				);
			}

			$mailler = new SendEmail();
			$response = $mailler->process($email);

			$articlesModel = new ArticlesModel();
			$contacts =  $articlesModel->findByTitle('_CONTACTS');
			$flashbag->add($response);

			return [
				'flashbag' => $flashbag->fetchMessage(),
				'contacts' => $contacts
			];
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


			return   [
				'_form' => $form,
				'flashbag' => $flashbag->fetchMessages(),
				'contacts' => $contacts
			];
		}
	}
}
