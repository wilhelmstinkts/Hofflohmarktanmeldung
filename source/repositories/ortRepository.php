<?php

namespace repositories;

use Ort\Ort;

include_once('connection.php');

// include_once('../ort/ort.php');
// include_once('../ort/koordinaten.php');

class OrtRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public static function getDefault()
    {
        return new OrtRepository(Connection::getDefaultConnection());
    }

    public function speichereOrt(Ort $ort)
    {
        $insertStatement = $this->pdo->prepare("INSERT INTO orte (strasse, hausnummer, koordinaten) VALUES (:strasse,:hausnummer,ST_GeomFromText(:koordinaten, 4326)) ON DUPLICATE KEY UPDATE strasse = strasse");
        $insertStatement->execute(array(
            ':strasse' => $ort->strasse,
            ':hausnummer' => $ort->hausnummer,
            ':koordinaten' => "POINT({$ort->koordinaten->breite} {$ort->koordinaten->laenge})"
        ));

        $getStatement = $this->pdo
            ->prepare("SELECT id FROM orte WHERE strasse = :strasse AND hausnummer = :hausnummer");
        $getStatement->execute(array(
                ':strasse' => $ort->strasse,
                ':hausnummer' => $ort->hausnummer
            ));
        $id = $getStatement->fetch()['id'];
        return $id;
    }
}
