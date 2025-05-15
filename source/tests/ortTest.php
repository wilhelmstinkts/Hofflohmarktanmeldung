<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Ort\Ort;
use Ort\Koordinaten;

final class OrtTest extends TestCase
{
    public function testVereinheitlicheStrasse(): void
    {
        $koordinaten = new Koordinaten(52.5200, 13.4050);
        $hausnummer = '1';
        
        $this->assertSame(new Ort('Hauptstr.', $hausnummer, $koordinaten)->strasse, 'Hauptstr.', 'Hauptstr. nicht vereinheitlicht');
        $this->assertSame(new Ort('Hauptstraße', $hausnummer, $koordinaten)->strasse, 'Hauptstr.', 'Hauptstraße nicht vereinheitlicht');
        $this->assertSame(new Ort('Hauptstrasse', $hausnummer, $koordinaten)->strasse, 'Hauptstr.', 'Hauptstrasse nicht vereinheitlicht');
        $this->assertSame(new Ort('Hauptstr', $hausnummer, $koordinaten)->strasse, 'Hauptstr.', 'Hauptstr nicht vereinheitlicht');
        $this->assertSame(new Ort('Hauptstrasse', $hausnummer, $koordinaten)->strasse, 'Hauptstr.', 'Hauptstrasse nicht vereinheitlicht');
        $this->assertSame(new Ort('Haupt Straße', $hausnummer, $koordinaten)->strasse, 'Hauptstr.', 'Haupt Straße nicht vereinheitlicht');
        $this->assertSame(new Ort('Haupt Strasse', $hausnummer, $koordinaten)->strasse, 'Hauptstr.', 'Haupt Strasse nicht vereinheitlicht');
    }

   
}
