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
        $this->role = ['Reader','Reader','Author','Admin'];
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

    
}