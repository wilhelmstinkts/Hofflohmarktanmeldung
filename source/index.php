<?php

use repositories\TeilnahmeRepository;
use Ort\Ort;

include_once('repositories/teilnahmeRepository.php');
include_once('ort/ort.php');
include_once('env.php');
$teilnahmeRepository = TeilnahmeRepository::getDefault();
$anmeldungen = $teilnahmeRepository->getAnmeldungenFuerTermin(1);
$markers = '[]';
if(count($anmeldungen) > 0)
{
    $markers = json_encode(array_map(fn (Ort $anmeldung) => array('lat' => $anmeldung->koordinaten->breite, 'lon' => $anmeldung->koordinaten->laenge), $anmeldungen));
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Hofflohmarkt Rosental - Wilhelmsruh</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="icon.png">
    <!-- Place favicon.ico in the root directory -->
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/style.css">
    <script type="module" src="script/script.js">
        const drawMapWithMarkers = drawMap({containerId: 'karte', attributionId: 'attribution', markers: <? echo $markers; ?>});
    </script>
</head>

<body onload='drawMap({containerId: "karte", attributionId: "mapAttribution", markers: <? echo $markers; ?>})'>

    <?
    if (isset($_GET['successMessage'])) {
        echo '<div class="message successMessage">' . urldecode($_GET['successMessage']) . '</div>';
    }

    if (isset($_GET['errorMessage'])) {
        echo '<div class="message errorMessage">' . urldecode($_GET['errorMessage']) . '</div>';
    }

    ?>
    <h1>Teilnehmende Höfe</h1>
    <div id="karte"></div>
    <div id="mapAttribution"></div>
    <?
    if (count($anmeldungen) == 0) {
        echo ('Noch keine Anmeldungen für den nächsten Termin');
    } else {
        echo ('<ol>');
        foreach ($anmeldungen as $anmeldung) {
            echo ("<li>{$anmeldung->strasse} {$anmeldung->hausnummer}</li>");
        }
        echo ('</ol>');
    }
    ?>
    <h1>Anmeldung</h1>
    <form action="anmeldung.php" method="post" aria-label="Anmeldeformular">
        <div><span style="display:none !important; visibility:hidden !important;"><label for="firstName">Bitte lasse dieses Feld leer.</label><input id="firstName" type="text" name="FirstName" value="" size="40" tabindex="-1" autocomplete="new-password"></span>
        </div>
        <div class="stack">
            <label for="email">E-Mail</label>
            <input required id="email" type="email" name="email" placeholder="max.mustermann@post.de" />
        </div>
        <div class="horizontal">
            <div class="stack" style="flex:2;">
                <label for="strasse">Straße</label>
                <input required minlength="5" type="text" id="strasse" name="strasse" placeholder="Hauptstraße" />
            </div>
            <div class="stack" style="flex:1;">
                <label for="hausnummer">Hausnummer</label>
                <input required minlength="1" type="text" id="hausnummer" name="hausnummer" placeholder="1" />
            </div>
        </div>
        <div class="stack"><label for="plz">Postleitzahl (nicht änderbar)</label>
            <input disabled value="13158" id="plz" name="plz" type="text" />
        </div>
        <div><input required type="checkbox" name="verantwortung" id="verantwortung" aria-invalid="false"><label for="verantwortung">Ich betreibe den Hofflohmarkt auf eigene Verantwortung.</label>
        </div>
        <div><input required type="checkbox" name="dsgvo" id="dsgvo" aria-invalid="false"><label for="dsgvo">Ich bin damit einverstanden, dass meine eingegebenen Daten an die Initiative Wilhelm gibt keine Ruh gesendet werden, sowie für diesen Hofflohmarkt und Zukünftige gespeichert werden.</label>
        </div>
        <input type="hidden" name="termin_id" value="1" />
        <div><input type="submit" value="Anmelden" />
        </div>
    </form>
</body>

</html>