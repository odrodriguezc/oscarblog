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
		$flashbag = new FlashBag();
		if ($userSession->isAuthenticated()==false) 
			/** Redirection vers le login */
			$http->redirectTo('/login/');
        
		if ($userSession->isAuthorized([1,2,3])==false)
		{
			$flashbag->add("Vous n'estes pas autorisé");
			$http->redirectTo('/admin/');
		}

		
		$catModel = new CategoriesModel();

		/**
		 * gateway 
		 * 
		 * variable tableau qui nous permet d'organiser le passage des variables à la vue
		 * 
		 * @var  array $catList liste des categories
		 * @var array fetchMessages appel à la methode fechtMessages de lac la class Flashbag
		 */
		$gateway = ['catList' => $catModel->orderCategories($catModel->listAll()),
					'flashbag' => $flashbag->fetchMessages()
					];

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
					$classBadge = 'badge-primary';
				else
					$classBadge = 'badge-primary';
				
				$html.= '<li class="list-group-item d-flex justify-content-between align-items-center">'.$category['title'].' <span><a href="'.$url.'/admin/categories/edit/?id='.$category['id'].'&pId='.$category['parentId'].'"><i class="far fa-edit"></i></a> 
					<a class="delLink" href="#delCatModal" data-id='.$category['id'].' data-parent='.$category['parentId'].'  data-title='.$category['title'].' data-toggle="modal" data-target="#delCatModal"><i class="icon-trash"></i></a>
				<span class="badge badge-pill '.$classBadge.'">'.$category['post'].' article(s)</span></span>';
				
				if(isset($category['childrens']))
					$html.= displayListeCategorie($category['childrens'], $url);
				$html.= '</li>';
			}
			
			$html.= '</ul>';

			return $html;
		}

		return  $gateway;
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