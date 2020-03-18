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

        /* Definitios des roles */
        $this->role = [ 'Author'=> 1,
                        'Editor'=> 2,
                        'Admin'=> 3];
    }

    /** Retourner un tableau de tous les users en base
     *
     * @param void
     * @return Array Jeu d'enregistrement représentant tous les users en base
     */
    public function listAll() 
    {
        return $this->dbh->query('SELECT * FROM '.$this->table);
    }

    /** Trouver un user avec son ID
     *
     * @param integer $id identifiant du user
     * @return Array Jeu d'enregistrement comportant le user trouvé
     */
    public function find($id)
    {
        return $this->dbh->queryOne('SELECT * FROM '.$this->table.' WHERE id = ?',[$id]);
    }

    
    /** Trouver un user avec son Email
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
        return  $this->dbh->executeSQL('DELETE FROM '.$this->table.' WHERE id=?',[$id]);
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
    public function add($username, $firstname, $lastname, $email, $password, $phone, $intro, $profile, $role, $status, $avatar) 
    {
        $registeredAtDate = date('Y-m-d');
        return $this->dbh->executeSQL('INSERT INTO '.$this->table.' (username, firstname, lastname, email, passwordHash, phone, intro, profile, role, status, avatar, registeredAt) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)',[$username, $firstname, $lastname, $email, $password, $phone, $intro, $profile, $role, $status, $avatar, $registeredAtDate]);
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