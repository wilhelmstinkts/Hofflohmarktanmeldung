<?php

use repositories\TeilnahmeRepository;
include_once('env.php');
include_once('repositories/teilnahmeRepository.php');

if (isset($_POST['code'])) {
    $teilnahmeRepo = TeilnahmeRepository::getDefault();
    $teilnahmeRepo->abmelden($_POST['code']);
    http_response_code(302);
    $message = urlencode("Sie haben Ihre Teilnahme abgesagt.");
    header('Location: index.php?successMessage=' . $message);
    exit;
}

if (!isset($_GET['code'])) {
    http_response_code(400);
    echo ('Der QueryParameter "code" fehlt');
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Abmeldung Hofflohmarkt</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="icon.png">
    <!-- Place favicon.ico in the root directory -->
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <form method="post">
        <input type="hidden" name="code" value="<? echo $_GET['code'] ?>" />
        <input type="submit" value="Abmelden" />
    </form>
</body>

</html>