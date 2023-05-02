<?php

namespace repositories;

use Ort\Ort;

// include('../ort/ort.php');
// include('../ort/koordinaten.php');

class OrtRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
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
