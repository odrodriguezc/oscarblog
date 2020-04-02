<?php

class CommentsModel
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
        $this->table = 'post_comment';

    }

    /** 
     * Retourner un tableau de tous les commentaires
     *
     * @param void
     * @return array Jeu d'enregistrement représentant tous les posts
     * @author ODRC
     */
    public function listAll():array 
    {
        return $this->dbh->query("SELECT * FROM {$this->table}");
    }

    /**
     * findByPostAuthor
     * 
     * Renvoit les commentaire assossiés aux articles d'un author donné
     * 
     * @param int $id id de l'author
     * @param int $limit = 1000 limit de resultat renvoyés
     * @return mixed array|false 
     * @author ODRC
     */
    public function findByPostAuthor(int $id, int $limit = 1000):array
    {
        $limitedStr = func_num_args() == 2 && $limit !=0 ? "LIMIT {$limit}" : '';
        return $this->dbh->query("SELECT 
                                        user.id AS userId,
                                        user.avatar AS userAvatar,
                                        post.id AS postId,
                                        post.title AS postTitle,
                                        post_comment.id AS commentId,
                                        post_comment.title AS commentTitle,
                                        post_comment.content AS commentContent,
                                        post_comment.authorId AS commentAuthorId,
                                        post_comment.createdAt AS commentCreatedAt,
                                        TIMESTAMPDIFF(MINUTE,
                                            post_comment.createdAt,
                                            CURRENT_TIMESTAMP) AS timePast
                                    FROM
                                        post_comment
                                            INNER JOIN
                                        post ON post.id = post_comment.postId
                                            INNER JOIN
		                                user ON user.id = post_comment.authorId
                                    WHERE
                                        post.authorId =? OR post_comment.authorId =?
                                    ORDER BY timePast ASC {$limitedStr}", 
                                    [$id, $id]
                                );                                    
    }


    /**
     * Supprimer un commentaire avec son id
     * @param integer $id identifian du post
     * @return int  dernier row
     */
    public function delete($id):int
    {
        return  $this->dbh->executeSQL('DELETE FROM '.$this->table.' WHERE id=?',[$id]);
    }


    


}