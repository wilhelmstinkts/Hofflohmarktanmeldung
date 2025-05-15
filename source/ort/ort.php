<?php

namespace Ort;

include_once('koordinaten.php');

class Ort
{
    public string $strasse;
    public string $hausnummer;
    public Koordinaten $koordinaten;

    private static function vereinheitlicheStrasse(string $strasse): string
    {
        $strasse = preg_replace('/\s+/', ' ', $strasse);
        $strasse = preg_replace('/(?<!\s)((str)|(straße)|(strasse))\.?\s*$/i', 'str.', $strasse);
        $strasse = preg_replace('/\s((str)|(straße)|(strasse))\.?\s*$/i', ' Str.', $strasse);
        return trim($strasse);
    }

    public function __construct(string $strasse, string $hausnummer, Koordinaten $koordinaten)
    {
        $this->strasse = Ort::vereinheitlicheStrasse($strasse);
        $this->hausnummer = $hausnummer;
        $this->koordinaten = $koordinaten;
    }


    public static function resolve(string $strasse, string $hausnummer, array $clientHeaders)
    {
        $apiResponse = Ort::getKoordinaten($strasse, $hausnummer, $clientHeaders);
        $first = json_decode($apiResponse, true)[0];
        $breite = (float) $first['lat'];
        $laenge = (float) $first['lon'];
        return new Ort($strasse, $hausnummer, new Koordinaten($breite, $laenge));
    }

    private static function getKoordinaten(string $strasse, string $hausnummer, array $clientHeaders)
    {
        $ch = curl_init();
        $adresse = urlencode($strasse . ' ' . $hausnummer);
        curl_setopt($ch, CURLOPT_URL, 'https://nominatim.openstreetmap.org/search.php?&format=jsonv2&city=Berlin&country=Germany&postalcode=13158&street=' . $adresse);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $headers = array();
        $headers[] = 'User-Agent: ' . $clientHeaders['User-Agent'];
        $headers[] = 'Accept: ' . $clientHeaders['Accept'];
        $headers[] = 'Accept-Language: ' . $clientHeaders['Accept-Language'];
        $headers[] = 'Accept-Encoding: ' . $clientHeaders['Accept-Encoding'];
        $headers[] = 'Dnt: 1';
        $headers[] = 'Connection: ' . $clientHeaders['Connection'];
        $headers[] = 'Referer: ' . $clientHeaders['Referer'];
        $headers[] = 'Upgrade-Insecure-Requests: ' . $clientHeaders['Upgrade-Insecure-Requests'];
        $headers[] = 'Sec-Fetch-Dest: ' . $clientHeaders['Sec-Fetch-Dest'];
        $headers[] = 'Sec-Fetch-Mode: ' . $clientHeaders['Sec-Fetch-Mode'];
        $headers[] = 'Sec-Fetch-Site: ' . $clientHeaders['Sec-Fetch-Site'];
        $headers[] = 'Sec-Fetch-User: ' . $clientHeaders['Sec-Fetch-User'];
        $headers[] = 'Sec-Gpc: 1';
        $headers[] = 'Te: trailers';

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return $result;
    }

    public static function sortiere(array &$orte)
    {
        usort(
            $orte,
            function (Ort $a, Ort $b) {
                if ($a->strasse == $b->strasse) {
                    $aZahl = preg_replace('/\D/', '', $a->hausnummer);
                    $bZahl = preg_replace('/\D/', '', $b->hausnummer);
                    if ((int) $aZahl - (int) $bZahl == 0) {
                        return strcmp($a->hausnummer, $b->hausnummer);
                    }
                    return (int) $aZahl - (int) $bZahl;
                }
                return strcmp($a->strasse, $b->strasse);
            }
        );
    }
}
