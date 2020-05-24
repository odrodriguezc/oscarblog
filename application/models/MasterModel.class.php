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

        return $this;
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

    /**
     * Undocumented function
     *
     * @param string $startDate au format "Y-m-d H:i:s"
     * @param string $endDate au format "Y-m-d H:i:s"
     * @param string $criteria propieté qui sert de sort key. champ de la table(createdAt, publishedAt, UpdatedAt)
     * @return 
     */
    public function countSince(string $criteria, string $startDate, string $endDate)
    {
        return $this->dbh->queryOne(
            "SELECT count(id) AS total_{$this->table}_{$criteria} FROM {$this->table} WHERE {$criteria} BETWEEN ? AND ?",
            [$startDate, $endDate]
        );
    }
}
