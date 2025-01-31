<?php

class ArticlesModel extends MasterModel
{
    /**
     * @var const constante avec la pattern regulier pour construir le slug
     * @author ODRC
     */
    private const SLUG_PATTERN = '/[^a-z0-9]+/i';


    /**
     * @var string Database table utilisée pour les requête
     */
    protected string $table = 'post';

    /** Retourner un tableau de tous les posts
     *
     * @param void
     * @return Array Jeu d'enregistrement représentant tous les posts
     */
    public function listAll()
    {
        return $this->dbh->query('SELECT post.id, post.title, post.metaTitle, post.summary, post.createdAt, post.published,  user.id AS authorId, user.username AS authorName FROM ' . $this->table . ' INNER JOIN user ON post.authorId = user.id ORDER BY post.createdAt DESC');
    }

    /** Retourner un tableau de tous les posts publiés
     *
     * @param int limit 
     * @return Array Jeu d'enregistrement représentant tous les posts
     */
    public function listPublishedAll(int $limit = 1000)
    {
        return $this->dbh->query("SELECT post.*,  user.id AS authorId, user.username AS authorName FROM {$this->table} INNER JOIN user ON post.authorId = user.id WHERE post.published = 1 ORDER BY post.publishedAt DESC LIMIT {$limit}");
    }

    /** Trouver un post avec son ID
     *
     * @param integer $id identifiant du post
     * @return Array Jeu d'enregistrement comportant le post
     */
    public function find($id)
    {
        return $this->dbh->queryOne(
            "SELECT post.id, 
                    post.title, 
                    post.metaTitle,
                    post.summary, 
                    post.createdAt, 
                    post.published, 
                    post.publishedAt, 
                    post.updatedAt, 
                    post.content, 
                    post.picture, 
                    post.likes, 
                    post.dislikes, 
                    post.share, 
                    user.id AS authorId, 
                    user.username AS authorName 
                    FROM {$this->table} 
                    INNER JOIN user ON post.authorId = user.id 
                    WHERE post.id = ?",
            [$id]
        );
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
        $slug = preg_replace("/-$/", "", preg_replace(self::SLUG_PATTERN, "-", strtolower($title)));
        if ($published == 1) {
            $date = new DateTime();
        } else {
            $date = new DateTime('1988-07-16');
        }
        $publishedAt = $date->format('Y-m-d H:i:s');
        return $this->dbh->executeSQL('INSERT INTO ' . $this->table . ' (title, metaTitle, slug, summary,  content, published, publishedAt, picture, authorId) VALUES (?,?,?,?,?,?,?,?,?)', [$title, $metaTitle, $slug, $summary,  $content, $published, $publishedAt,  $picture, $authorId]);
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
    public function update(int $id, string $title, string $metaTitle, string $summary, string $content, int $published, string $picture)
    {
        if ($published == 1) {
            $date = new DateTime();
        } else {
            $date = new DateTime('1988-07-16');
        }
        $publishedAt = $date->format('Y-m-d H:i:s');
        $slug = preg_replace("/-$/", "", preg_replace(self::SLUG_PATTERN, "-", strtolower($title)));
        $this->dbh->executeSQL('UPDATE ' . $this->table . ' SET title=?, slug=?, metaTitle=?, summary=?, content=?, published=?, publishedAt=?, picture=? WHERE id=?', [$title, $slug, $metaTitle, $summary, $content, $published, $publishedAt, $picture, $id]);
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
        return $this->dbh->queryOne("SELECT * FROM $this->table WHERE title=?", [$title]);
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
        return $this->dbh->queryOne("SELECT * FROM $this->table WHERE title=? AND id!=?", [$title, $id]);
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

        $limitedStr = func_num_args() == 2 && $limit != 0 ? "LIMIT {$limit}" : '';
        return $this->dbh->query('SELECT *, TIMESTAMPDIFF(MINUTE,updatedAt,CURRENT_TIMESTAMP) AS timePast FROM ' . $this->table . ' WHERE authorId=? ORDER BY updatedAt ' . $limitedStr . ' ', [$id]);
    }


    /**
     * setAction
     * 
     * Ajoute une unité sur le count de l'action
     * @param int id
     * @param string action (likes, dislikes, share)
     * @return mixed 
     * @author ODRC
     */
    public function setAction(int $id, string $action)
    {
        return $this->dbh->executeSql("UPDATE {$this->table} SET {$action}={$action} + 1 WHERE id=?", [$id]);
    }

    /**
     * countAction
     * 
     * Return le total pour une action (likes, dislikes, share)
     * 
     * @param int id
     * @param string action 
     * @return int 
     * @author ODRC
     */
    public function countAction(int $id, string $action)
    {
        return $this->dbh->queryOne("SELECT {$action} FROM {$this->table} WHERE id=?", [$id]);
    }

    public function findByCategory(int $id)
    {
        return $this->dbh->query("SELECT post.*,  user.id AS authorId, user.username AS authorName, category.id, category.title as categoryTitle, category.description as categoryDescription 
        FROM {$this->table} 
        INNER JOIN user ON post.authorId = user.id 
        INNER JOIN post_has_category ON post.id = post_has_category.postId
        INNER JOIN category ON post_has_category.categoryId = category.id
        WHERE post_has_category.categoryId = $id
        ORDER BY post.createdAt DESC", [$id]);
    }
}
