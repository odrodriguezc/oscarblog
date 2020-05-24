<?php

class TokensModel extends MasterModel
{
    /**
     * @var string Database table utilisée pour les requête
     */
    protected string $table = 'token';

    public function generate(string $token, string $email)
    {
        return $this->dbh->executeSql("INSERT INTO {$this->table} (token, email) VALUES (?,?)", [$token, $email]);
    }

    public function check(string $token)
    {
        return $this->dbh->queryOne("SELECT * FROM {$this->table} WHERE token = ?", [$token]);
    }
}
