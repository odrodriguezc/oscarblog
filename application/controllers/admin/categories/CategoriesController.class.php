<?php

class CategoriesController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP GET
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
		*/
	
		/** 
		  * UserSession - instance de la classe session
		  * 
		  * - isAutheticated va nous permettre de savoir si l'utilisateur est connecté 
		*/
		$userSession = new UserSession();
		if ($userSession->isAuthenticated()==false) 
			/** Redirection vers le login */
			$http->redirectTo('/login/');
        
        if ($userSession->isAuthorized([1,2,3])==false)
            /** Redirection vers le referer */
			header("location: {$_SERVER['HTTP_REFERER']}");
		
		$catModel = new CategoriesModel();
		$catList = $catModel->orderCategories($catModel->listAll());

		/** Function récursive (qui s'appelle elle même) permettant d'afficher un tableau 
		 * hiérarchisée à nombre infini d'enfants et sous enfants
		 * Exceptionnellement nous ne pouvons faire l'affichage dans la vue et utilisons donc une fonction pour générer notre liste
		 * @param array $categories le tableau (jeu d'enregistrement) des catégories
		 */
		function displayListeCategorie($categories,$url)
		{
			$html = '<ul class="list-group">';
			foreach($categories as $category)
		{
			if($category['post'] > 0)
				$classBadge = 'badge-success';
			else
				$classBadge = 'badge-light';
			
			$html.= '<li class="list-group-item">'.$category['title'].' <a href="'.$url.'/admin/categories/edit/?id='.$category['id'].'"><i class="far fa-edit"></i></a> 
				<a href="<?= $requestUrl ?>/admin/categories/del/?id='.$category['id'].'" data-toggle="modal" data-target="#delCatModal"><i class="icon-trash"></i></a>
			<span class="badge badge-pill '.$classBadge.'">'.$category['post'].' article(s)</span>';
			if(isset($category['childrens']))
				$html.= displayListeCategorie($category['childrens'], $url);
			$html.= '</li>';
		}
			$html.= '</ul>';

			return $html;
		}

		

		
		return  ['catList' => $catList,
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