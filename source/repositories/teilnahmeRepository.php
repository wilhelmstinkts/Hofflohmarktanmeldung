<?php

namespace repositories;
include_once('connection.php');

class TeilnahmeRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public static function getDefault()
    {
        return new TeilnahmeRepository(Connection::getDefaultConnection());
    }

    public function anmelden(int $ortId, int $terminId, string $email, string $absagecode)
    {
        $insertStatement = $this->pdo->prepare(
            "INSERT INTO teilnahmen (termin_id, ort_id, email, absagecode, angemeldet_am) VALUES (:terminId,:ortId,:email,:absagecode,CURRENT_DATE)");
        $insertStatement->execute(array(
            ':terminId' => $terminId ,
            ':ortId' => $ortId ,
            ':email' => $email ,
            ':absagecode' => $absagecode 
        ));
    }

    public function abmelden(string $absagecode)
    {
        $updateStatement = $this->pdo->prepare(
            "UPDATE teilnahmen SET abgemeldet_am = CURRENT_DATE WHERE absagecode = :absagecode");
        $updateStatement->execute(array(
            ':absagecode' => $absagecode 
        ));
    }
}
