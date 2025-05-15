<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Ort\Ort;
use Ort\Koordinaten;

final class OrtTest extends TestCase
{
    public function testVereinheitlicheStrasse(): void
    {
        $koordinaten = new Koordinaten(52.5200, 13.4050);
        $hausnummer = '1';

        $this->assertSame('Hauptstr.', new Ort('Hauptstr.', $hausnummer, $koordinaten)->strasse, 'Hauptstr. nicht vereinheitlicht');
        $this->assertSame('Hauptstr.', new Ort('Hauptstraße', $hausnummer, $koordinaten)->strasse, 'Hauptstraße nicht vereinheitlicht');
        $this->assertSame('Hauptstr.', new Ort('Hauptstrasse', $hausnummer, $koordinaten)->strasse, 'Hauptstrasse nicht vereinheitlicht');
        $this->assertSame('Hauptstr.', new Ort('Hauptstr', $hausnummer, $koordinaten)->strasse, 'Hauptstr nicht vereinheitlicht');
        $this->assertSame('Hauptstr.', new Ort('Hauptstrasse', $hausnummer, $koordinaten)->strasse, 'Hauptstrasse nicht vereinheitlicht');
        $this->assertSame('Alte Str.', new Ort('Alte Straße', $hausnummer, $koordinaten)->strasse, 'Alte Straße nicht vereinheitlicht');
        $this->assertSame('Alte Str.', new Ort('Alte straße', $hausnummer, $koordinaten)->strasse, 'Alte straße nicht vereinheitlicht');
    }

    public function testSortiertNachStrasse(): void
    {
        $ort1 = new Ort('Bergstr.', '1', new Koordinaten(52.5200, 13.4050));
        $ort2 = new Ort('Alte Str.', '2', new Koordinaten(52.5200, 13.4050));
        $ort3 = new Ort('Zukunftsweg', '3', new Koordinaten(52.5200, 13.4050));
        $orte = array($ort1, $ort2, $ort3);
        Ort::sortiere($orte);
        $this->assertSame('Alte Str.', $orte[0]->strasse, 'Erster Ort nicht korrekt sortiert');
        $this->assertSame('Bergstr.', $orte[1]->strasse, 'Zweiter Ort nicht korrekt sortiert');
        $this->assertSame('Zukunftsweg', $orte[2]->strasse, 'Dritter Ort nicht korrekt sortiert');
    }

    public function testSortiertNachHausnummer(): void
    {
        $ort1 = new Ort('Bergstr', '1', new Koordinaten(52.5200, 13.4050));
        $ort2 = new Ort('Bergstr', '20', new Koordinaten(52.5200, 13.4050));
        $ort3 = new Ort('Bergstr', '103A', new Koordinaten(52.5200, 13.4050));
        $ort4 = new Ort('Bergstr', '103B', new Koordinaten(52.5200, 13.4050));
        $orte = array($ort2, $ort4, $ort1, $ort3);
        Ort::sortiere($orte);
        $this->assertSame($ort1->hausnummer, $orte[0]->hausnummer);
        $this->assertSame($ort2->hausnummer, $orte[1]->hausnummer);
        $this->assertSame($ort3->hausnummer, $orte[2]->hausnummer);
        $this->assertSame($ort4->hausnummer, $orte[3]->hausnummer);
    }
}
