<?php

abstract class MasterModel
{
    /**
     * @var Database Objet Database pour effectuer des requête
     */
    protected $dbh;

    public function __construct()
    {
        $this->dbh = new Database();
    }
}
