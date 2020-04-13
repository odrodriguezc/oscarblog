<?php

class GalleryModel
{
     /**
     * @var Database Objet Database pour effectuer des requête
     */
    private $dbh;

    /**
     * @var string Database table utilisée pour les requête
     */
    private $table;

      /**
     * @var string Database table has post utilisée pour les requête
     */
    private $tableHas;

    /**  Constructeur
     *
     * @param void
     * @return void
     */
    public function __construct()
    {
        $this->dbh = new Database();
        $this->table = 'picture';
        $this->tableHas = 'picture_has_collection';

    }

    /** 
     * Retourner un tableau de tous les pictures uploades par le user
     *
     * @param int user
     * @return Array Jeu d'enregistrement représentant tous les pictures en base
     */
    public function listAll(int $userId) 
    {
        return $this->dbh->query("SELECT * FROM {$this->table} WHERE userId = ?",[$userId] );
    }

    /**
     * 
     */

    /** Trouver une picture avec son ID
     *
     * @param integer $id identifiant 
     * @return Array Jeu d'enregistrement comportant le picture trouvé
     */
    public function find($id)
    {
        return $this->dbh->queryOne('SELECT * FROM '.$this->table.' WHERE id = ?',[$id]);
    }

    /**
     * findByUser
     * 
     * Cherche les photos enregistrés en bdd crées par l'author passé en parametre
     * 
     * - Si le parametre limit est passé limite le nombre de lignes à la valeur saisi 
     * 
     * @param int $id 
     * @param int $limit limite de lignes (rows) à envoyer dans la requete 
     * @return array|bool jeu d'énregistrement | false
     * @author ODRC
     */
    public function findByUser(int $id, int $limit = 1000): array
    {

        $limitedStr = func_num_args() == 2 && $limit !=0 ? "LIMIT {$limit}" : '';
        return $this->dbh->query('SELECT *, TIMESTAMPDIFF(MINUTE,uploadAt,CURRENT_TIMESTAMP) AS timePast FROM '.$this->table.' WHERE userId=? ORDER BY timePast '.$limitedStr.' ',[$id]);
    }


    /**
     * Supprimer une picture avec son id
     * @param integer $id identifian 
     * @return int  
     */
     public function delete($id):int
     {
        return $this->dbh->executeSQL('DELETE FROM '.$this->table.' WHERE id=?',[$id]);

     }

    
    /**
     * add
     * 
     * ajoute une image dans la bdd
     * @param string uniqueName
     * @param string label
     * @param string description
     * @param int userId
     * @param string metadata
     */
    public function add(string $uniqueName, string $label, string $description, int $userId, string $metadata)
    {
        return $this->dbh->executeSql("INSERT INTO {$this->table} 
                                    (uniqueName, label, description, userId, metadata) 
                                    VALUES (?,?,?,?,?)",
                                    [$uniqueName, $label, $description, $userId, $metadata]
                                    );
    }

    /**
     * update
     * 
     * @param int id
     * @param string label
     * @param string description
     * @author ODRC
     */
    public function update(int $id, string $label, string $description)
    {
        return $this->dbh->executeSql("UPDATE {$this->table} SET label=?, description=? WHERE id=?",[$label, $description, $id]);
    }


    /////////////////////////
    ////// COLECTIONS ////////
    /////////////////////////

    /**
     * listPicByCollectionOne
     * 
     * photos dans une collection 
     * @param int $colId
     * @return array
     * @author ODRC
     */
    public function listPicByCollectionOne(int $colId)
    {
        return $this->dbh->query("SELECT 
                                        pic.*, 
                                        col.id AS collectionId,
                                        col.title AS collectionTitle,
                                        col.description AS collectionDescription,
                                        col.published AS collectionPublished,
                                        col.createdAt AS collectionCreatedAt,
                                        col.updatedAt AS collectionUpdatedAt,
                                        u.username AS username
                                    FROM
                                        blog.picture AS pic
                                            INNER JOIN
                                        blog.picture_has_collection AS col_has ON col_has.pictureId = pic.id
                                            INNER JOIN
                                            blog.picture_collection AS col ON col.id = col_has.collectionId
                                        INNER JOIN 
                                        blog.user AS u ON u.id = col.userId
                                    WHERE 
                                        col.published = 1 AND col.id = ?",
                                [$colId]);
    }


    /**
     * listPublicCollections
     * 
     * Liste des collections existantes qui on été publiées
     * @return array
     * @author ODRC
     */
    public function listPublicCollections()
    {
        return $this->dbh->query("SELECT picture_collection.*, user.username FROM picture_collection INNER JOIN user on user.id = picture_collection.userId WHERE picture_collection.published = 1");
    }


    /**
     * listbyCollectionsAll
     * 
     * liste de tous les collections 
     * @param void
     * @return array
     * @author ODRC
     */
    public function listByCollectionAll()
    {
        return $this->dbh->query("SELECT 
                                        pic.*, 
                                        col.id AS collectionId,
                                        col.title AS collectionTitle,
                                        col.description AS collectionDescription,
                                        col.published AS collectionPublished,
                                        col.createdAt AS collectionCreatedAt,
                                        col.updatedAt AS collectionUpdatedAt,
                                        u.username AS username
                                    FROM
                                        blog.picture AS pic
                                            INNER JOIN
                                        blog.picture_has_collection AS col_has ON col_has.pictureId = pic.id
                                            INNER JOIN
                                ",[]);

    }

    /**
     * listByPublicCollections
     * 
     * liste de tous les collections 
     * @param void
     * @return array
     * @author ODRC
     */
    public function listByPublicCollectionAll()
    {
        return $this->dbh->query("SELECT 
                                        pic.*, 
                                        col.id AS collectionId,
                                        col.title AS collectionTitle,
                                        col.description AS collectionDescription,
                                        col.published AS collectionPublished,
                                        col.createdAt AS collectionCreatedAt,
                                        col.updatedAt AS collectionUpdatedAt,
                                        u.username AS username
                                    FROM
                                        blog.picture AS pic
                                            INNER JOIN
                                        blog.picture_has_collection AS col_has ON col_has.pictureId = pic.id
                                            INNER JOIN
                                            blog.picture_collection AS col ON col.id = col_has.collectionId
                                           INNER JOIN 
                                        blog.user AS u ON u.id = col.userId
                                    WHERE 
                                        col.published = 1
                                ",[]);

    }

    /**
     * listByCollection
     * 
     * Liste les photos ajoutes aux collections
     * 
     * @param int user 
     * @return mixed array | false 
     * @author ODRC
     */
    public function listByCollection(int $userId)
    {
        return $this->dbh->query("SELECT 
                                        pic.*, 
                                        col.id AS collectionId,
                                        col.title AS collectionTitle,
                                        col.description AS collectionDescription,
                                        col.published AS collectionPublished,
                                        col.createdAt AS collectionCreatedAt,
                                        col.updatedAt AS collectionUpdatedAt
                                    FROM
                                        blog.picture AS pic
                                            INNER JOIN
                                        blog.picture_has_collection AS col_has ON col_has.pictureId = pic.id
                                            INNER JOIN
                                        blog.picture_collection AS col ON col.id = col_has.collectionId
                                    WHERE
                                        pic.userId = ?",[$userId]);
    }

    /**
     * findCollections
     * 
     * Liste des noms des collections by user
     * 
     * @param int user
     * @return mixed 
     * @author ODRC
     */
    public function findCollections(int $userId)
    {
        return $this->dbh->query("SELECT 
                                        *
                                    FROM
                                        blog.picture_collection AS col
                                    WHERE
                                        col.userId = ?;",
                                    [$userId]
                                );
    }

    /**
     * findCollectionById
     */

    /**
     * findCollectionsByPic
     * 
     * Cherche les collections auxquelles apartient une photo
     * 
     * @param int picId
     * @return mixed
     * @author ODRC
     */
    public function findCollectionsByPic(int $picId)
    {
        return $this->dbh->query("SELECT 
                                        *
                                    FROM
                                        blog.picture_collection AS col
                                            INNER JOIN
                                        blog.picture_has_collection AS colHas ON col.id = colHas.collectionId
                                    WHERE
                                        colHas.pictureId = ?", [$picId]);
    }

    /**
     * delHasRelations
     * 
     * Supprime les entrées du post dans la table post_has_category
     * 
     * @param int picId
     * @return void 
     */
    public function delHasRelation(int $picId)
    {
        return $this->dbh->executeSQL("DELETE FROM {$this->tableHas} WHERE pictureId = ?", [$picId]);
    }

     /**
     * Ajouter des pics  dans une collection
     * 
     * @param int picId 
     * @param array collectionId
     * @return void
     * 
     * @author odrc
     */
    public function addToCollections(int $picId, array $collectionId)
    {
        foreach ($collectionId as  $colId) 
        {
            return $this->dbh->executeSql("INSERT INTO {$this->tableHas} (pictureId, collectionId) VALUES (?,?)",[$picId, $colId]);
        }
    }

    /**
     * 
     * popOffPic
     * Fait sauter une image d'une collection
     * 
     * @param int picId
     * @param int colId
     * @return mixed 
     * @author ODRC
     */
    public function popOffPic(int $picId, int $colId)
    {
        return $this->dbh->executeSql("DELETE FROM {$this->tableHas} 
                                        WHERE pictureId = ? AND collectionId = ?", 
                                        [$picId, $colId]);
    }
    
    /**
     * findColByTitle
     * 
     * cherche les collection avec le titre
     * @param string $title
     * @return mixed
     * @author ODRC
     *
     */
    public function findColByTitle(string $title)
    {
        return $this->dbh->query("SELECT * FROM picture_collection WHERE title=?",[$title]);
    }

    /**
     * newCollection
     * 
     * Cree une nouvelle collection
     * @param int $userId
     * @param string $title
     * @param string $description
     * @param int $published
     * @return mixed
     * @author ODRC
     */
    public function newCollection(int $userId, string $title, string $description='', int $published=0)
    {
        return $this->dbh->executeSql("INSERT INTO 
                                        picture_collection (userId, title, description, published)
                                        VALUES(?,?,?,?)",
                                        [$userId, $title, $description, $published]);
    }
}