<?php

namespace repositories;

use Ort\Koordinaten;
use Ort\Ort;

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

    public function getAnmeldungenFuerTermin(int $terminId)
    {
        $getStatement = $this->pdo
            ->prepare("SELECT DISTINCT o.strasse, o.hausnummer, ST_X(o.koordinaten) as breite, ST_Y(o.koordinaten) as laenge FROM teilnahmen t JOIN orte o ON t.ort_id = o.id WHERE t.termin_id = :terminId AND t.abgemeldet_am IS NULL");
        $getStatement->execute(array(
                ':terminId' => $terminId
            ));
        $result = $getStatement->fetchAll();
        return array_map(fn(array $arr): Ort => new Ort($arr['strasse'], $arr['hausnummer'], new Koordinaten($arr['breite'], $arr['laenge'])), $result);
    }

}
