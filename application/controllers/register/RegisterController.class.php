<?php

use Email\Email;
use Email\SendEmail;

class RegisterController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {

        $flashbag = new FlashBag();

        if (!array_key_exists('token', $queryFields) || $queryFields['token'] === '') {
            $flashbag->add("Vous n'est pas autorisé");
            $http->redirectTo('/');
        }

        $validator = new DataValidation();
        $token = $validator->inputFilter($queryFields['token']);

        $tokenModel = new TokensModel();
        $preUser = $tokenModel->check($token);
        if (empty($preUser)) {
            $flashbag->add("Vous n'est pas autorisé");
            $http->redirectTo("/");
        } else {
            $email = $preUser['email'];
            $createdAt = new DateTime($preUser['createdAt']);
        }

        $today = new DateTime('now');
        $interval = $createdAt->diff($today);
        if ($interval->days > 60) {
            $flashbag->add("Votre invitation n'est plus valable, veilleuz en demander une autre.");
            $http->redirectTo("/contact/");
        }


        return [

            'flashbag' => $flashbag->fetchMessages(),
            '_raw_template' => '',
            'email' => $email
        ];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        $flashbag = new FlashBag();

        try {

            $validator = new DataValidation();
            //On vérifie que tous les champs obligatoires sont remplis 

            $validator->obligatoryFields(
                [
                    'username' => $formFields['username'],
                    'email' => $formFields['email'],
                    'password' => $formFields['password'],
                    'passwordConfirm' => $formFields['passwordConfirm']
                ]
            );

            //securisation de la donné 
            $data = $validator->formFilter($formFields);

            //username 
            $validator->username($data['username']);

            // format attendu : courriel
            $validator->email($data['email']);

            //password
            if ($validator->password($data['password'], $data['passwordConfirm']))
                /**Chifrage du mot de pass avec la methode HASH */
                $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);


            /**
             * verification d'unicité du username et du mail 
             * 
             * - Requete pour recouperer les users qu'on soit le même username soit le même email mais qui n'ont pas le même id
             * 
             * - Si l'on obtient au moins un user on boucle pour verifier si c'est le username et/ou le email qui est déjà a été trouvé
             * 
             * - On envoie des erreur en fonction de mail ou username répeté
             * @author ODRC
             */
            $usersModel = new UsersModel();
            $matchUsers = $usersModel->findByUsernameOrEmail($data['username'], $data['email']);

            if ($matchUsers != false) {
                foreach ($matchUsers as $key => $matchUser) {
                    if ($matchUser['username'] === $data['username'])
                        $validator->addError("le nom d'utilisateur \"{$data['username']}\" est déjà existant");

                    if ($matchUser['email'] === $data['email'])
                        $validator->addError("le mail \"{$data['email']}\" est déjà existant");
                }
            }

            if (empty($validator->getErrors()) != true) {
                throw new DomainException("DExc - Erreur de validation des champs du formulaire");
            }

            /** Enregistrer les données dans la base de données */
            $usersModel->add(
                $data['username'],
                '',
                '',
                $data['email'],
                $passwordHash,
                '',
                'intro',
                'profile',
                '1',
                '1',
            );

            $mailler = new SendEmail();
            $welcomeMail = new Email(
                $data['email'],
                'contact.oscarblog@gmail.com',
                "Bienvenu-e- dans notre communauté",
                "<p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Non, dolorum velit libero sequi illum maxime?</p>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Veritatis inventore ex recusandae perspiciatis! Obcaecati, inventore nemo neque officiis aperiam fugit sint dolores ad, repudiandae sunt officia repellendus itaque deleniti velit nihil hic numquam nisi perspiciatis illum tempore? Ipsam, quisquam officiis!</p>"
            );
            $mailler->process($welcomeMail);

            /** Ajout du flashbag */
            $flashbag->add('L\'utilisateur a bien été ajouté. Vous pouvez completer votre profil en accedant au back-office');

            $http->redirectTo('/login/');
        } catch (DomainException $exception) {

            /** Réaffichage du formulaire avec un message d'erreur. */
            $form = new UsersForm();
            /** On bind nos données $_POST ($formFields) avec notre objet formulaire */
            $form->bind($formFields);
            //liste d'erreur dans le formulair avec le message pour chaq'un
            $form->setErrorMessage($validator->getErrors());
            //erreur lancé dans l'exeption
            $form->addError($exception->getMessage());

            $usersModel = new UsersModel();
            return  [
                '_form' => $form,
                'roles' => $usersModel->role,
                '_raw_template' => ''
            ];
        }
    }
}
