<?php

namespace repositories;

include_once('connection.php');

class TerminRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public static function getDefault()
    {
        return new TerminRepository(Connection::getDefaultConnection());
    }

    public function getNaechstenTermin()
    {
        $getStatement = $this->pdo
            ->prepare("SELECT id, DATE_FORMAT(datum, '%e.%c.%Y') as datum FROM termine WHERE datum >= CURRENT_DATE ORDER BY datum LIMIT 1");
        $getStatement->execute();
        $fetchResult = $getStatement->fetch();
        if($fetchResult == false)
        {
            return null;
        }
        return $fetchResult;
    }
}
