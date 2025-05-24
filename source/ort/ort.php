<?php

namespace Ort;

use repositories\OrtRepository;
use utils\RetryUtils;

include_once('koordinaten.php');
include_once('koordinaten.php');
include_once(__DIR__ . '/../utils/withRetry.php');

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


    public static function resolve(string $strasseIn, string $hausnummer, array $clientHeaders)
    {
        $strasse = Ort::vereinheitlicheStrasse($strasseIn);
        $hausnummer = trim($hausnummer);
        $bekannteKoordinaten = OrtRepository::getDefault()->getGespeicherteKoordinaten($strasse, $hausnummer);
        if ($bekannteKoordinaten !== null) {            
            return new Ort($strasse, $hausnummer, $bekannteKoordinaten);
        }
        // Früher gab es keine Vereinheitlichung der Straßennamen
        $bekannteKoordinatenWieGegegeben = OrtRepository::getDefault()->getGespeicherteKoordinaten(
            trim($strasseIn),
            $hausnummer
        );
        if($bekannteKoordinatenWieGegegeben !== null) {
            return new Ort($strasseIn, $hausnummer, $bekannteKoordinatenWieGegegeben);
        }
        $apiResponse = RetryUtils::withRetry(function () use ($strasse, $hausnummer, $clientHeaders) {
            return Ort::getKoordinaten($strasse, $hausnummer, $clientHeaders);
        });

        return new Ort($strasse, $hausnummer, $apiResponse);
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

        $errorMessage = 'Koordinaten konnten nicht ermittelt werden. Bitte überprüfe die Adresse oder versuche es später nochmals.';

        if ($result === false) {
            throw new \Exception($errorMessage);
        }

        $json = json_decode($result, true);
        if (count($json) === 0) {
            throw new \Exception($errorMessage);
        }
        $first = $json[0];
        if (!isset($first['lat']) || !isset($first['lon'])) {
            throw new \Exception($errorMessage);
        }
        $breite = (float) $first['lat'];
        $laenge = (float) $first['lon'];
        return new Koordinaten($breite, $laenge);
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
