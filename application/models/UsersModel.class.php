<?php

class UsersModel extends MasterModel
{

    /**
     * @var string Database table utilisée pour les requête
     */
    protected string $table = 'user';

    /**
     * role
     * 
     * @var Role Array contenant les roles des utilisateurs
     * @author ODRC
     */
    public $role = [
        'Author' => 1,
        'Editor' => 2,
        'Admin' => 3
    ];

    /** Retourner un tableau de tous les users en base
     *
     * @param void
     * @return Array Jeu d'enregistrement représentant tous les users en base
     */
    public function listAll()
    {
        return $this->dbh->query('SELECT * FROM ' . $this->table);
    }


    /** Trouver un user avec son Email
     *
     * @param string $email email du user
     * @return array Jeu d'enregistrement comportant le user trouvé
     */
    public function findByEmail($email)
    {
        return $this->dbh->queryOne('SELECT * FROM ' . $this->table . ' WHERE email = ?', [$email]);
    }

    /** Trouver un user avec son username
     *
     * @param string $username usenrame du user
     * @return array|bool Jeu d'enregistrement comportant le user trouvé|false
     */
    public function findByUsername($username)
    {
        return $this->dbh->queryOne('SELECT * FROM ' . $this->table . ' WHERE username = ?', [$username]);
    }


    /** Ajouter un user en base
     *
     * @param string $firstname
     * @param string $lastname
     * @param string $username
     * @param string $email
     * @param string $password
     * @param string $phone
     * @param string $intro
     * @param string $profil
     * @param int $role
     * @param string $status
     * @param string $image
     * @return void
     */
    public function add($username, $firstname, $lastname, $email, $password, $phone, $intro, $profile, $role = 1, $status = '1', $avatar = NULL)
    {
        $registeredAtDate = date('Y-m-d');
        return $this->dbh->executeSQL('INSERT INTO ' . $this->table . ' (username, firstname, lastname, email, passwordHash, phone, intro, profile, role, status, avatar, registeredAt) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)', [$username, $firstname, $lastname, $email, $password, $phone, $intro, $profile, $role, $status, $avatar, $registeredAtDate]);
    }

    /** Modifie un utilisateur en base
     *
     * @param integer $id identifiant de l'utilisateur
     * @param string $username nom d'utilisateur 
     * @param string $firstname prenom
     * @param string $lastname nom
     * @param string $email email
     * @param string $password mot de pass
     * @param string $phone telephone
     * @param string $intro description courte
     * @param string $profil description longue
     * @param int $role role
     * @param string $status status
     * @param string $avatar image
     * @return void
     */
    public function update($username, $firstname, $lastname, $email, $password, $phone, $intro, $profile, $role, $status, $avatar, $id)
    {
        $this->dbh->executeSQL('UPDATE ' . $this->table . ' SET username=?, firstname=?, lastname=?, email=?, passwordHash=?, phone=?, intro=?, profile=?, role=?, status=?, avatar=? WHERE id=?', [$username, $firstname, $lastname, $email, $password, $phone, $intro, $profile, $role, $status, $avatar, $id]);
    }

    /** 
     * Update Login de l'utilisateur connecté
     * 
     * @param  integer $id 
     * @return void
     * 
     * @author ODRC
     */
    public function updateLogin(int $id)
    {
        $Logindate = date('Y-m-d, H:i:s');
        $this->dbh->executeSQL('UPDATE ' . $this->table . ' SET lastLogin=? WHERE id=?', [$Logindate, $id]);
    }

    /**
     * findByUsernameOrEmail
     * 
     * Recherche un utilisateur dans la BDD avec son username et/ou son mail et un id differnte 
     * 
     * - sert à determiner si les changes saisis ne rentrent pas en conflic avec les utilisateurs enregistrés en bdd
     * -  saisir le parametre facultatif id lorsqu'il s'agit d'une update et laisir la valeur pas defaut pour les newUsers
     * 
     * @param string $username 
     * @param string $email
     * @param int $id par defaut -1, car c'est une valuer inexistante en BDD
     * @return array|bool jeu d'enregistrement | false
     * 
     * @author ODRC
     */
    public function findByUsernameOrEmail(string $username, string $email, int $id = -1)
    {
        return $this->dbh->query(
            "SELECT 
                username, 
                email 
            FROM {$this->table} 
            WHERE (username=? OR email=?) AND id!=?",
            [$username, $email, $id]
        );
    }

    /**
     * findRecentActivity
     * 
     * Recherche les dernieres articles postés par le user et/ou les dernieres commentaires sur les articles du user
     * 
     * @param int $id id de l'utilisateur 
     * @param int $limit limite de lignes (rows) à envoyer dans la requete 
     * @return array|bool jeu d'énregistrement | false
     * 
     * @author ODRC
     */
    public function findRecentActivity(int $id, int $limit)
    {
        $limitedStr = func_num_args() == 2 && $limit != 0 ? "LIMIT {$limit}" : '';
        return $this->dbh->query(
            "SELECT 
                p.id AS postId,
                p.title AS postTitle,
                p.picture,
                p.likes,
                p.dislikes,
                p.share,
                p.metaTitle,
                p.authorId,
                TIMESTAMPDIFF(MINUTE,
                    p.updatedAt,
                    CURRENT_TIMESTAMP) AS postPastTime,
                c.id AS commentId,
                c.title AS commentTitle,
                C.createdAt AS commentCreatedAt,
                TIMESTAMPDIFF(MINUTE,
                    c.createdAt,
                    CURRENT_TIMESTAMP) AS commentPastTime
            FROM
                post AS p
                    LEFT JOIN
                post_comment AS c ON (P.id = c.postId)
            WHERE
                p.author_id = 14
            ORDER BY commentPastTime DESC , postPastTime DESC
            {$limitedStr}"
        );
    }
}
