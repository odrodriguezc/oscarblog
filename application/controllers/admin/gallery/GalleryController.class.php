<?php

class GalleryController
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
		if ($userSession->isAuthenticated() == false)
			/** Redirection vers le login */
			$http->redirectTo('/login/');

		if ($userSession->isAuthorized([1, 2, 3]) == false) {
			$flashbag->add("Vous n'estes pas autorisé");
			$http->redirectTo('/admin/');
		}

		$userId = $userSession->getId();
		$picModel = new GalleryModel();
		$picList = $picModel->listAll(intval($userId));
		$picsByCollection = $picModel->listByCollection(intval($userId));
		$sortPicsByCollection = $picModel->sortPicByColletion($picsByCollection);

		unset($picsByCollection);


		return [
			'picList' => $picList,
			'picsByCollection' => $sortPicsByCollection,
			'flashbag' => $flashbag->fetchMessages(),
			'pageTitle' => $http->getRequestFile()
		];
	}

	public function httpPostMethod(Http $http, array $formFields)
	{

		set_time_limit(600);
		$userSession = new UserSession();
		$flashbag = new FlashBag();
		if ($userSession->isAuthenticated() == false)
			/** Redirection vers le login */
			$http->redirectTo('/login/');

		if ($userSession->isAuthorized([1, 2, 3]) == false) {
			$flashbag->add("Vous n'estes pas autorisé");
			$http->redirectTo('/admin/');
		}

		$userId = $userSession->getId();
		$galleryModel = new GalleryModel();
		$validator = new DataValidation();

		try {
			$images = $validator->sortUploadedFiles($_FILES['file']);
			$data = [];
			$uploadSucces = [];

			foreach ($images as $key => $image) {

				$pictureNameBd = '';
				if (isset($image) && $image['error'] == 0) {

					$picture = new Upload($image);
					if ($picture->file_is_image) {

						if ($picture->uploaded) {

							//taille original
							$uniqName = uniqid('gallery_');
							$oldName = $picture->file_src_name_body . '.' . $picture->file_src_name_ext;
							$picture->file_new_name_body = "bg_" . $uniqName;
							$picture->file_overwrite = true;

							if ($picture->file_src_name_ext != 'jpg')
								$picture->image_convert = 'jpg';

							$picture->process(WWW_PATH . "/assets/images/gallery/");
							//taille medium
							$picture->file_new_name_body = "md_" . $uniqName;
							$picture->image_resize = true;
							$picture->image_x = 1200;
							$picture->image_y = 600;
							$picture->file_overwrite = true;

							if ($picture->file_src_name_ext != 'jpg')
								$picture->image_convert = 'jpg';

							$picture->process(WWW_PATH . "/assets/images/gallery/");
							//taille small
							$picture->file_new_name_body = "sm_" . $uniqName;
							$picture->image_resize = true;
							$picture->image_x = 600;
							$picture->image_ratio_y = true;
							$picture->file_overwrite = true;

							if ($picture->file_src_name_ext != 'jpg')
								$picture->image_convert = 'jpg';

							$picture->process(WWW_PATH . "/assets/images/gallery/");

							if ($picture->processed) {
								//nom pour la bdd
								$pictureNameBd = $uniqName . '.' . $picture->file_dst_name_ext;
								$metadata = exif_read_data($picture->file_dst_pathname) ? json_encode(exif_read_data($picture->file_dst_pathname)) : 'NULL';
								//bdd informations 
								$data[$key] = [
									'uniqueName' => $pictureNameBd,
									'label' => $oldName,
									'description' => '',
									'userId' => $userId,
									'metadata' => $metadata,
									'upload' => true,
								];
							} else {
								$validator->addError($picture->error);
							}
						} else {
							$validator->addError($picture->error);
						}
					} else {
						$validator->addError("le fichier {$image['name']} n'est au format valable");
					}
				} else {
					$validator->addError("un erreur c'est produit lors du téléchargement de l'image {$image['name']}");
				}
			}

			if (empty($data)) {
				throw new DomainException("Aucun fichier n'a pas été uploadé", 1);
			}

			//requtes
			foreach ($data as $key => $item) {
				$lastRow = $galleryModel->add(
					$item['uniqueName'],
					$item['label'],
					$item['description'],
					intval($item['userId']),
					$item['metadata']
				);

				if (isset($lastRow)) {
					$uploadSucces[] = "l'image {$item['label']} a été uploadée avec succes";
				} else {
					$validator->addError("Une erreur c'est produit lors du téléchargement de l'image {$item['label']}");
					//supp fichier 
					if (file_exists(WWW_PATH . "\assets\images\gallery\bg_{$item['uniqueName']}"))
						unlink(WWW_PATH . "\assets\images\posts\bg_{$item['uniqueName']}");
					if (file_exists(WWW_PATH . "\assets\images\gallery\md_{$item['uniqueName']}"))
						unlink(WWW_PATH . "\assets\images\posts\md_{$item['uniqueName']}");
					if (file_exists(WWW_PATH . "\assets\images\gallery\sm_{$item['uniqueName']}"))
						unlink(WWW_PATH . "\assets\images\posts\sm_{$item['uniqueName']}");
				}
				//ajouter a la collection??


			}
		} catch (DomainException $exception) {
			//afficher les erreur de validatons et upload					
			/** Réaffichage du formulaire avec un message d'erreur. */
			$validator->addError($exception->getMessage());

			$http->redirectTo('admin/gallery/');
		}

		$picModel = new GalleryModel();
		$picsByCollection = $picModel->listByCollection(intval($userId));

		return  [
			'errorMessages' => $validator->getErrors(),
			'uploadSucces' => $uploadSucces,
			'picList' => $galleryModel->listAll($userId),
			'pageTitle' => $http->getRequestFile(),
			'picList' => $picModel->listAll(intval($userId)),
			'picsByCollection' => $picModel->sortPicByColletion($picsByCollection)

		];
	}
}
