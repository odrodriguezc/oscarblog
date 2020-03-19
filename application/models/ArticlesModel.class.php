<?php

class ArticlesModel
{
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
        return $this->dbh->query('SELECT post.id, post.title, post.metaTitle, post.summary, post.createdAt, post.published,  user.id AS author_id, user.username AS author_name FROM '.$this->table.' INNER JOIN user ON post.author_id = user.id ORDER BY post.createdAt DESC');
    }

    /** Trouver un post avec son ID
     *
     * @param integer $id identifiant du post
     * @return Array Jeu d'enregistrement comportant le post
     */
    public function find($id)
    {
        return $this->dbh->queryOne('SELECT post.id, post.title, post.metaTitle, post.summary, post.createdAt, post.published, post.publishedAt, post.updatedAt, post.content, post.picture, post.like, post.dislike, post.share, user.id AS author_id, user.username AS author_name FROM '.$this->table.' INNER JOIN user ON post.author_id = user.id WHERE post.id = ?',[$id]);
    }

    /**
     * Supprimer un post avec son id
     * @param integer $id identifian du post
     * @return void 
     */
    public function delete($id)
    {
        return  $this->dbh->executeSQL('DELETE FROM '.$this->table.' WHERE id=?',[$id]);
    }


    /** Ajouter un post en base
     *
     * @param string $title
     * @param string $metaTitle
     * @param string $summary
     * @param string $content
     * @param string $picture
     * @param integer $authorId   
     * @return void
     */
    public function add(string $title, string $metaTitle, string $summary, string $content, string $picture, int $authorId) 
    {   
        $slug = $title;
        $createdAt = date('Y-m-d, H:i:s');
        return $this->dbh->executeSQL('INSERT INTO '.$this->table.' (title, metaTitle, slug, summary, createdAt, content, picture, author_id) VALUES (?,?,?,?,?,?,?,?)',[$title, $metaTitle, $slug, $summary, $createdAt, $content, $picture, $authorId]);
    }

    /**
     * Mofifier un article
     * 
     * @param int $id
     * @param string $title
     * @param string $metaTitle
     * @param string $summary
     * @param string $content
     * @param string $picture
     * @return int $updatedId id du dernier article updated
     */
    public function update(int $id, string $title, string $metaTitle, string $summary, string $content, string $picture )
    {
        $slug = $title;
        $this->dbh->executeSQL('UPDATE '.$this->table.' SET title=?, slug=?, metaTitle=?, summary=?, content=?, picture=? WHERE id=?',[$title, $slug, $metaTitle, $summary, $content, $picture, $id]); 

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


}