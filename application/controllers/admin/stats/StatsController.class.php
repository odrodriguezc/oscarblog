<?php

class StatsController
{
	public function httpGetMethod(Http $http, array $queryFields)
	{
		$articlesModel = new ArticlesModel();
		$commentsModel = new CommentsModel();
		$picModel =  new GalleryModel();
		$date = new DateTime();
		$end = $date->format('Y-m-d H:i:s');

		//week
		$start = $date->sub(new DateInterval('P7D'));
		$startStr = $start->format('Y-m-d H:i:s');
		$published = $articlesModel->countSince('publishedAt', $startStr, $end);
		$created = $articlesModel->countSince('createdAt', $startStr, $end);
		$coments = $commentsModel->countSince('createdAt', $startStr, $end);
		$pics = $picModel->countSince('uploadAt', $startStr, $end);

		$week = [
			'createdPost' => array_values($created)[0],
			'publishedPost' => array_values($published)[0],
			'comments' => array_values($coments)[0],
			'pics' => array_values($pics)[0]
		];

		//months
		$startM = $date->sub(new DateInterval('P1M'));
		$startStrM = $start->format('Y-m-d H:i:s');
		$publishedM = $articlesModel->countSince('publishedAt', $startStrM, $end);
		$createdM = $articlesModel->countSince('createdAt', $startStrM, $end);
		$comentsM = $commentsModel->countSince('createdAt', $startStrM, $end);
		$picsM = $picModel->countSince('uploadAt', $startStrM, $end);

		$month = [
			'createdPost' => array_values($createdM)[0],
			'publishedPost' => array_values($publishedM)[0],
			'comments' => array_values($comentsM)[0],
			'pics' => array_values($picsM)[0]
		];

		$stats = ['week' => $week, 'month' => $month];


		echo (json_encode($stats, JSON_NUMERIC_CHECK));
		die();


		return [
			'_raw_template' => true
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
