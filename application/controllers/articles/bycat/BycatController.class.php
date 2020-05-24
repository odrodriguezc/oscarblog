<?php


class BycatController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {

        $ArticlesModel = new ArticlesModel();
        $flashbag = new FlashBag();

        if (!array_key_exists('id', $queryFields) || $queryFields['id'] === '') {

            $http->redirectTo('/home/');
        }
        $validator = new DataValidation();
        $catId = $validator->inputFilter($queryFields['id']);


        return [
            'articlesList' => $ArticlesModel->findByCategory(intval($catId)),
            'presentation' => $ArticlesModel->findByTitle('_PRESENTATION'),
            'flashbag' => $flashbag->fetchMessages()
        ];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        /*
    	 * Méthode appelée en cas de requête HTTP POST
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
    	 */
    }
}
