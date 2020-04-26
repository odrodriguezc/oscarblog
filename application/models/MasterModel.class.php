<?php

abstract class MasterModel
{
    /**
     * @var Database Objet Database pour effectuer des requête
     */
    protected Database $dbh;

    /**
     * table
     *
     * @var [string] $table nom de la table
     */
    protected string $table;

    public function __construct()
    {
        $this->dbh = new Database();
    }

    /**
     * Cherche une entité avec son idé
     *
     * @param integer $id
     * @return array|false le row correspondant ou false si l'id n'existe pas 
     */
    public function findById(int $id)
    {
        return $this->dbh->queryOne("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
    }

    /**
     * Supprimer un element
     * @param integer $id identifian
     * @return mixted 
     */
    public  function delete(int $id)
    {
        return  $this->dbh->executeSQL("DELETE FROM {$this->table}  WHERE id=?", [$id]);
    }
}
