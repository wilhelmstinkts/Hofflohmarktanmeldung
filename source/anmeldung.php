<?php    
    include_once('ort/ort.php');
    include_once('repositories/ortRepository.php');
    include_once('repositories/teilnahmeRepository.php');
    include_once('mail/mailer.php');

use Mail\Mailer;
use Ort\Ort as Ort;
    use repositories\OrtRepository;
    use repositories\TeilnahmeRepository;    

    if(isset($_POST['FirstName']) && $_POST['FirstName'])
    {
        http_response_code(302);
        $message = urlencode('Du hast ein verbotenes Feld ausgefüllt.');
        header('Location: index.php?errorMessage=' . $message);
        exit;
    }

    $strasse = $_POST['strasse'];
    $hausnummer = $_POST['hausnummer'];
    $ort = Ort::resolve($strasse, $hausnummer, apache_request_headers());
    
    $ortRepository = OrtRepository::getDefault();
    $ortId = $ortRepository->speichereOrt($ort);

    $absageCode = bin2hex(random_bytes(20));
    $teilnahmeRepository = TeilnahmeRepository::getDefault();
    $teilnahmeRepository->anmelden($ortId, $_POST['termin_id'], $_POST['email'], $absageCode);
    Mailer::sendeAnmeldeBestaetigung($_POST['email'], $absageCode);

    http_response_code(302);
    $message = urlencode("Die Anmeldung wurde verarbeitet.");
    header('Location: index.php?successMessage=' . $message);
