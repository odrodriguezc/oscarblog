<?php
/**
 * -  Dans la BDD il y a un article entitulé '_ABOUT' qui sert de format pour la view about
 * @author ODRC
 */

class AboutController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
    	
		
		$articlesModel = new ArticlesModel();
		
		
		return ['about' => $articlesModel->findByTitle('_ABOUT')];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    	exit();		
    }
}