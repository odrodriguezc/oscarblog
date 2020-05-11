<?php

class ArticleController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {

        $ArticlesModel = new ArticlesModel();
        $userSession = new UserSession();
        $flashbag = new FlashBag();

        if (!array_key_exists('id', $queryFields) || $queryFields['id'] === '') {
            $flashbag->add('Un article doit etre indiqué');
            $http->redirectTo('/home/');
        }

        $commentsModel = new CommentsModel();
        $validator = new DataValidation();
        $postId = $validator->inputFilter($queryFields['id']);

        $article = $ArticlesModel->find($postId);
        $comments = $commentsModel->listByPost($postId);

        $gateway =  [
            'article' => $article,
            'flashbag' => $flashbag->fetchMessages(),
            'comments' => $comments,
            'logged' => $userSession->isAuthenticated()
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
    }
}
