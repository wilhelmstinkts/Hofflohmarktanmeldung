<?php    
    include_once('ort/ort.php');
    include_once('repositories/ortRepository.php');
    include_once('repositories/teilnahmeRepository.php');
    include_once('mail/mailer.php');
    include_once('env.php');

use Mail\Mailer;
use Ort\Ort as Ort;
    use repositories\OrtRepository;
    use repositories\TeilnahmeRepository;    

    if(isset($_POST['FirstName']) && $_POST['FirstName'])
    {
        http_response_code(302);
        header('Location: index.php?errorMessage=verbotenesFeld');
        exit;
    }

    $strasse = $_POST['strasse'];
    $hausnummer = $_POST['hausnummer'];
    try{
    $ort = Ort::resolve($strasse, $hausnummer, apache_request_headers());
    } catch (\Exception $e) {
        http_response_code(302);
        header('Location: index.php?errorMessage=koordinatenFehler');
        exit;
    }
    
    $ortRepository = OrtRepository::getDefault();
    $ortId = $ortRepository->speichereOrt($ort);

    $absageCode = bin2hex(random_bytes(20));
    $teilnahmeRepository = TeilnahmeRepository::getDefault();
    $teilnahmeRepository->anmelden($ortId, $_POST['termin_id'], $_POST['email'], $absageCode);
    Mailer::sendeAnmeldeBestaetigung($_POST['email'], $absageCode);

    http_response_code(302);
    
    header('Location: index.php?successMessage=anmeldung');
