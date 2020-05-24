<?php

class CommentController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        $http->redirectTo('/Articles/');
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        $userSession = new UserSession();
        $validator = new DataValidation;
        $commentsModel = new CommentsModel();

        try {
            //CLASIC
            $validator->obligatoryFields([
                'title' => $formFields['title'],
                'content' => $formFields['content'],
                'postId' => $formFields['postId']
            ]);

            //securisation de la donné 
            $data = $validator->formFilter($formFields);

            //longueur des champs
            $validator->lengtOne($data['title'], 'title', 255, 0);
            $validator->lengtOne($data['content'], 'content', 50000);

            if (empty($validator->getErrors()) == false) {
                throw new DomainException("Error Processing Request", 1);
            }
            $authorId = $userSession->getId();

            /** Enregistrer les données dans la base de données */
            $commentsModel->add(
                $data['title'],
                $data['content'],
                intval($data['postId']),
                intval($authorId)
            );
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
        }
        $http->redirectTo("/articles/article/?id={$data['postId']}");
    }
}
