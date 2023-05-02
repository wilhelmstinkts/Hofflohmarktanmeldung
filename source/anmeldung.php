<?php    
    include('ort/ort.php');
    include('repositories/ortRepository.php');
    use Ort\Ort as Ort;
    use repositories\OrtRepository;

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
    $pdo = new \PDO('mysql:dbname=d03ce714;host=mysql', 'root', 'totallyunsafe', array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    $ortRepository = new OrtRepository($pdo);
    $id = $ortRepository->speichereOrt($ort);
    http_response_code(302);
    $message = urlencode("Die Anmeldung wurde verarbeitet. {$id}");
    header('Location: index.php?successMessage=' . $message);
