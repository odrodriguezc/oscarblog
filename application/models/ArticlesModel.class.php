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
     * @param integer $id
     * @param string $title
     * @param string $metaTitle
     * @param string $slug 
     * @param string $summary
     * @param int $published
     * @param string $content
     * @param string $picture
     * @param integer $authorId   
     * @return void
     */
    public function add(int $id, string $title, string $metaTitle, string $slug, string $summary, int $published, string $content, string $picture, int $authorId) 
    {
        $createdAt = date('Y-m-d, H:i:s');
        return $this->dbh->executeSQL('INSERT INTO '.$this->table.' (id, title, metaTitle, slug, summary, published, createdAt, content, picture, author_id) VALUES (?,?,?,?,?,?,?,?,?,?)',[$id, $title, $metaTitle, $slug, $summary, $published, $createdAt, $content, $picture, $authorId]);
    }

     

    /** Update Login de l'utilisateur connecté
     * @param  integer $id 
     * @return void
     */
    public function updateLogin(int $id)
    {   
        $Logindate = date('Y-m-d, H:i:s');
        $this->dbh->executeSQL('UPDATE '.$this->table.' SET lastLogin=? WHERE id=?', [$Logindate, $id]);
    }
    
}