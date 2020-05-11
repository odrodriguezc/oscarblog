<?php

class ImageController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {

        $userSession = new UserSession();
        $flashbag = new FlashBag();
        if ($userSession->isAuthenticated() == false)
            /** Redirection vers le login */
            $http->redirectTo('/login/');

        if ($userSession->isAuthorized([1, 2, 3]) == false) {
            $flashbag->add("Vous n'estes pas autorisé");
            $http->redirectTo('/admin/');
        }

        /**
         * Si on accede sans especifier un querystring ou en le laisant vide on envoie une message en flashbag et on redirige vers l'admin
         */
        if (!array_key_exists('id', $queryFields) || $queryFields['id'] === '') {
            $flashbag->add('Une image  doit etre indiqué');
            $http->redirectTo('/admin/gallery/');
        }

        $galleryModel = new GalleryModel();
        $form = new PictureForm();
        $validator = new DataValidation();
        $dataId = $validator->inputFilter($queryFields['id']);

        $pic = $galleryModel->findById(intval($dataId));
        $collectionList = $galleryModel->findCollections(intval($userSession->getId()));
        $picCollections = $galleryModel->findCollectionsByPic(intval($dataId));
        $picCollections ? $picCollectionsId = array_column($picCollections, 'id') : 'NULL';

        // Ajouter vrai ou faux a las collectiones affectés ou pas
        foreach ($collectionList as $key => &$col) {
            if ($picCollections && in_array($col['id'], $picCollectionsId)) {
                $col['assigned'] = 'true';
            } else {
                $col['assigned'] = 'false';
            }
        }
        unset($picCollectionsId);



        $form->bind(array(
            'id' => $pic['id'],
            'label' => $pic['label'],
            'description' => $pic['description'],
            'uniqueName' => $pic['uniqueName'],
            'published' => $pic['published'],
            'posted' => $pic['posted'],
            'metadata' => $pic['metadata'],
            'likes' => $pic['likes'],
            'dislikes' => $pic['dislikes'],
            'share' => $pic['share'],
            'userId' => $pic['userId'],
            'uploadAt' => $pic['uploadAt'],
            'collections' => $picCollections ? $picCollections : 'NULL'
        ));


        return [
            '_form' => $form,
            'collectionList' => $collectionList,
            'pageTitle' => $http->getRequestFile()
        ];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {

        $userSession = new UserSession();
        $flashbag = new Flashbag();
        if ($userSession->isAuthenticated() == false)
            /** Redirection vers le login */
            $http->redirectTo('/login/');

        if ($userSession->isAuthorized([1, 2, 3]) == false)
            /** Redirection vers le dashboard */
            $http->redirectTo('/admin/');

        try {
            //instance de la classe DataValidation
            $validator = new DataValidation;

            //verifications des champs obligatoires
            $validator->obligatoryFields(array('label' => $formFields['label']));
            //securisation de la donné 
            $data = $validator->formFilter($formFields);

            //longueur des champs
            $validator->lengtOne($data['label'], 'Nom', 150);
            $validator->lengtOne($data['description'], 'Description', 500);

            if (!empty($validator->getErrors())) {
                throw new DomainException("Form: Erreur de validations des champs du formulaire", 1);
            }

            $galleryModel = new GalleryModel();
            $galleryModel->update(
                $data['id'],
                $data['label'],
                $data['description']
            );


            /** Ajout du flashbag */
            $flashbag->add('L\'image a bien été modifiée');

            /** Redirection vers la liste */
            $http->redirectTo("admin/gallery/image/?id={$data['id']}");
        } catch (DomainException $exception) {
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
