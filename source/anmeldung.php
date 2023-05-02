<?php    
    include('ort/ort.php');
    use Ort\Ort as Ort;

    if(isset($_POST['FirstName']) && $_POST['FirstName'])
    {
        http_response_code(302);
        $message = urlencode('Du hast ein verbotenes Feld ausgefüllt.');
        header('Ort: index.php?errorMessage=' . $message);
        exit;
    }

    $strasse = $_POST['strasse'];
    $hausnummer = $_POST['hausnummer'];
    $ort = Ort::resolve($strasse, $hausnummer, apache_request_headers());

    http_response_code(302);
    $message = urlencode('Die Anmeldung wurde verarbeitet.');
    header('Ort: index.php?successMessage=' . $message);   

?>