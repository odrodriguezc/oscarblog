<?php

class UsersModel
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
     * @var Role Array contenant les roles des utilisateurs
     */
    public $role;

    /**  Constructeur
     *
     * @param void
     * @return void
     */
    public function __construct()
    {
        $this->dbh = new Database();
        $this->table = 'user';

        /* Role's definition */
        $this->role = [ 'Reader'=> '1',
                        'Author'=> '2',
                        'Admin'=> '3'];
    }

    /** Retourne un tableau de tous les users en base
     *
     * @param void
     * @return Array Jeu d'enregistrement représentant tous les users en base
     */
    public function listAll() 
    {
        return $this->dbh->query('SELECT * FROM '.$this->table);
    }

    /** Trouve un user avec son ID
     *
     * @param integer $id identifiant du user
     * @return Array Jeu d'enregistrement comportant le user trouvé
     */
    public function find($id)
    {
        return $this->dbh->queryOne('SELECT * FROM '.$this->table.' WHERE id = ?',[$id]);
    }

    
    /** Trouve un user avec son Email
     *
     * @param string $email email du user
     * @return Array Jeu d'enregistrement comportant le user trouvé
     */
    public function findByEmail($email)
    {
        return $this->dbh->queryOne('SELECT * FROM '.$this->table.' WHERE email = ?',[$email]);
    }

    /**
     * Supprimer un user avec son id
     * @param integer $id identifian du user
     * @return void 
     */
     public function delete($id)
     {
         //probleme avec la cle etrangere qui nepermet pas de supprimer le row
        return  $this->dbh->executeSQL('DELETE FROM '.$this->table.' WHERE id=?',[$id]);
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
        $this->dbh->executeSQL('UPDATE '.$this->table.' SET username=?, firstname=?, lastname=?, email=?, passwordHash=?, phone=?, intro=?, profile=?, role=?, status=?, avatar=? WHERE id=?',[$username, $firstname, $lastname, $email, $password, $phone, $intro, $profile, $role, $status, $avatar, $id]); 
    }
    
}