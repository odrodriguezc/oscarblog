<?php

class DelController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        $userSession = new UserSession();
        $flashbag = new FlashBag();
        if ($userSession->isAuthenticated() == false)
            /** Redirection vers le login */
            $http->redirectTo('/login/');

        /**
         * Si on accede sans especifier un querystring ou en le laisant vide on envoie une message en flashbag et on redirige vers l'admin
         */
        if (!array_key_exists('id', $queryFields) || $queryFields['id'] === '') {
            $flashbag->add('Une image doit etre indiqué pour la supprimer');
            $http->redirectTo('/admin/gallery/');
        }

        $picModel = new GalleryModel();
        $validator = new DataValidation();
        $dataId = $validator->inputFilter($queryFields['id']);
        $pic = $picModel->findById($dataId);
        //Si l'utilisateur n'est pas autorisé
        if ($pic == false) {
            $flashbag->add("L'image n'a pas été trouvé");
            $http->redirectTo('/admin/gallery/');
        }

        if ($userSession->isAuthorized([3]) == false) {
            // si l'utilisateur n'est pas l'auteur de l'pic
            if ($pic['userId'] != $userSession->getId()) {
                $flashbag->add("Vous n'etes pas autorisé à supprimer cette image");
            }
            $http->redirectTo('/admin/gallery/');
        }


        //supprimer l'ancien image
        if ($pic['uniqueName'] != NULL && file_exists(WWW_PATH . "\assets\images\posts\bg_{$pic['uniqueName']}"))
            unlink(WWW_PATH . "\assets\images\posts\bg_{$pic['uniqueName']}");
        if ($pic['uniqueName'] != NULL && file_exists(WWW_PATH . "\assets\images\posts\md_{$pic['uniqueName']}"))
            unlink(WWW_PATH . "\assets\images\posts\md_{$pic['uniqueName']}");
        if ($pic['uniqueName'] != NULL && file_exists(WWW_PATH . "\assets\images\posts\sm_{$pic['uniqueName']}"))
            unlink(WWW_PATH . "\assets\images\posts\sm_{$pic['uniqueName']}");

        /**
         * @todo implementer la diferentiation lorsque la requete delete n'abouti pas
         */
        $picModel->delete(intval($dataId));

        $flashbag->add('L\'image a bien été supprimé');


        $http->redirectTo('admin/gallery/');
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        $http->redirectTo('admin/gallery/');
    }
}
