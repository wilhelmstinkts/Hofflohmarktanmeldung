<?php
    include 'locationResolver/locationResolver.php';

    if(isset($_POST['FirstName']) && $_POST['FirstName'])
    {
        http_response_code(302);
        $message = urlencode('Du hast ein verbotenes Feld ausgefüllt.');
        header('Location: index.php?errorMessage=' . $message);
        exit;
    }

    $strasse = $_POST['strasse'];
    $hausnummer = $_POST['hausnummer'];
    $message = urlEncode(getCoordinates($strasse, $hausnummer, apache_request_headers()));
    // $message = urlEncode(json_encode(apache_request_headers()));


    http_response_code(302);
    // $message = urlencode('Die Anmeldung wurde verarbeitet.');
    header('Location: index.php?successMessage=' . $message);   

?>