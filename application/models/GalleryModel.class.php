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

    /**  Constructeur
     *
     * @param void
     * @return void
     */
    public function __construct()
    {
        $this->dbh = new Database();
        $this->table = 'picture';

    }

    /** Retourner un tableau de tous les pictures en base
     *
     * @param void
     * @return Array Jeu d'enregistrement représentant tous les pictures en base
     */
    public function listAll() 
    {
        return $this->dbh->query('SELECT * FROM '.$this->table);
    }

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
     * Supprimer une picture avec son id
     * @param integer $id identifian 
     * @return int  
     */
     public function delete($id):int
     {
        return $this->dbh->executeSQL('DELETE FROM '.$this->table.' WHERE id=?',[$id]);

     }

    

}