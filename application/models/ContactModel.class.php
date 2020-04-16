<?php

class ContactModel
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
        $this->table = 'contact';

    }

    /**
     * addMessage
     * 
     * Ajoute une message en bdd
     * 
     * @param string email
     * @param string name
     * @param string message
     * @return mixed
     * @author ODRC
     */
    public function addMessage(string $email, string $message, string $name='')
    {
        return $this->dbh->executeSql("INSERT INTO {$this->table} (email, name, message) VALUES (?,?,?)",[$email, $name, $message]);
    }

   

}