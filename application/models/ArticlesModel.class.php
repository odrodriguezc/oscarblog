<?php

class ArticlesModel
{
    /**
     * @var const constante avec la pattern regulier pour construir le slug
     * @author ODRC
    */
    private const SLUG_PATTERN = '/[^a-z0-9]+/i';

    /**
     * @var Database Objet Database pour effectuer des requête
    */
    private $dbh;

    /**
     * @var string Database table utilisée pour les requête
     */
    private $table;

    /**  Constructeur
     *
     * @param void
     * @return void
     */
    public function __construct()
    {
        $this->dbh = new Database();
        $this->table = 'post';

    }

    /** Retourner un tableau de tous les posts
     *
     * @param void
     * @return Array Jeu d'enregistrement représentant tous les posts
     */
    public function listAll() 
    {
        return $this->dbh->query('SELECT post.id, post.title, post.metaTitle, post.summary, post.createdAt, post.published,  user.id AS authorId, user.username AS authorName FROM '.$this->table.' INNER JOIN user ON post.authorId = user.id ORDER BY post.createdAt DESC');
    }

    /** Trouver un post avec son ID
     *
     * @param integer $id identifiant du post
     * @return Array Jeu d'enregistrement comportant le post
     */
    public function find($id)
    {
        return $this->dbh->queryOne("SELECT post.id, 
                                            post.title, 
                                            post.metaTitle,
                                            post.summary, 
                                            post.createdAt, 
                                            post.published, 
                                            post.publishedAt, 
                                            post.updatedAt, 
                                            post.content, 
                                            post.picture, 
                                            post.like, 
                                            post.dislike, 
                                            post.share, 
                                            user.id AS authorId, 
                                            user.username AS authorName 
                                            FROM {$this->table} 
                                            INNER JOIN user ON post.authorId = user.id 
                                            WHERE post.id = ?",[$id]
                                        );
    }

    /**
     * Supprimer un post avec son id
     * @param integer $id identifian du post
     * @return void 
     */
    public function delete(int $id)
    {
        return  $this->dbh->executeSQL('DELETE FROM '.$this->table.' WHERE id=?',[$id]);
    }


    /** 
     * Ajouter un post en base
     *
     * @param string $title
     * @param string $metaTitle
     * @param string $summary
     * @param string $content
     * @param int published
     * @param string $picture
     * @param integer $authorId   
     * @return int lastInsertId
     * @author ODRC
     */
    public function add(string $title, string $metaTitle, string $summary, string $content, int $published, string $picture, int $authorId) 
    {   
        $slug = preg_replace("/-$/","",preg_replace(self::SLUG_PATTERN, "-", strtolower($title)));
        $createdAt = date('Y-m-d, H:i:s');
        if ($published ==1) 
        {
            $publishedAt= $createdAt;
        } else {
            $publishedAt = '';
        }
        return $this->dbh->executeSQL('INSERT INTO '.$this->table.' (title, metaTitle, slug, summary, createdAt, content, published, publishedAt, picture, authorId) VALUES (?,?,?,?,?,?,?,?,?,?)',[$title, $metaTitle, $slug, $summary, $createdAt, $content, $published, $publishedAt, $picture, $authorId]);
    }

    /**
     * Mofifier un article
     * 
     * @param int $id
     * @param string $title
     * @param string $metaTitle
     * @param string $summary
     * @param string $content
     * @param int published
     * @param string $picture
     * @return int $updatedId id du dernier article updated
     * @author ODRC
     */
    public function update(int $id, string $title, string $metaTitle, string $summary, string $content, int $published, string $picture )
    {
        if ($published ==1) 
        {
            $publishedAt= date('Y-m-d, H:i:s');
        } else {
            $publishedAt = '';
        }
        $slug = preg_replace("/-$/","",preg_replace(self::SLUG_PATTERN, "-", strtolower($title)));
        $this->dbh->executeSQL('UPDATE '.$this->table.' SET title=?, slug=?, metaTitle=?, summary=?, content=?, published=?, publishedAt=?, picture=? WHERE id=?',[$title, $slug, $metaTitle, $summary, $content, $published, $publishedAt, $picture, $id]); 

    }

    /**
     * findByTitle 
     * 
     * - Cherche un article dans la BDD en prenant comme parametre de recherche le titre
     * 
     * @param string $title - nouvau titre saisi par l'author 
     * @return array|bool jeu d'enregistrement de la requete | false
     * 
     */
    public function findByTitle(string $title)
    {
       return $this->dbh->queryOne("SELECT * FROM $this->table WHERE title=?",[$title]);
    }

    /**
     * findByNewTitle 
     * 
     * - Cherche dans la BDD s'il y a un autre article different de celui qui est modifié qui a le nouvea titre
     * @author ODRC
     * 
     * @param string $newTitle nouveau titre 
     * @param int $id id de l'article 
     * @return array|bool jeu d'enregistrement | false si aucun article n'est trouvé
     */
    public function findByNewTitle(string $title, int $id)
    {
        return $this->dbh->queryOne("SELECT * FROM $this->table WHERE title=? AND id!=?",[$title, $id]); 
    }

    /**
     * findByAuthor
     * 
     * Cherche les articles enregistrés en bdd crées par l'author passé en parametre
     * 
     * - Si le parametre limit est passé limite le nombre de lignes à la valeur saisi 
     * 
     * @param int $id 
     * @param int $limit limite de lignes (rows) à envoyer dans la requete 
     * @return array|bool jeu d'énregistrement | false
     * @author ODRC
     */
    public function findByAuthor(int $id, int $limit = 1000): array
    {

        $limitedStr = func_num_args() == 2 && $limit !=0 ? "LIMIT {$limit}" : '';
        return $this->dbh->query('SELECT *, TIMESTAMPDIFF(MINUTE,updatedAt,CURRENT_TIMESTAMP) AS timePast FROM '.$this->table.' WHERE authorId=? ORDER BY updatedAt '.$limitedStr.' ',[$id]);
    }

   

}