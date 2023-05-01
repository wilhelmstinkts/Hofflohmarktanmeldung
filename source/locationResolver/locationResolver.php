<?php

function getCoordinates(string $strasse, string $hausnummer, array $clientHeaders)
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
