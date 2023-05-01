<head>
    <meta charset="utf-8">
    <title>Hofflohmarkt Rosental - Wilhelmsruh</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="site.webmanifest">
    <link rel="apple-touch-icon" href="icon.png">
    <!-- Place favicon.ico in the root directory -->
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <?
    if(isset($_GET['successMessage']))
    {
        echo '<div class="message successMessage">' . urldecode($_GET['successMessage']) . '</div>';
    }

    if(isset($_GET['errorMessage']))
    {
        echo '<div class="message errorMessage">' . urldecode($_GET['errorMessage']) . '</div>';
    }

    ?>
    <form action="anmeldung.php" method="post" aria-label="Anmeldeformular">
        <div><span style="display:none !important; visibility:hidden !important;"><label for="firstName">Bitte lasse dieses Feld leer.</label><input id="firstName" type="text" name="FirstName" value="" size="40" tabindex="-1" autocomplete="new-password"></span>
        </div>
        <div class="stack">
            <label for="email">E-Mail</label>
            <input required id="email" type="email" name="email" placeholder="max.mustermann@post.de"/>
        </div>
        <div class="horizontal full-width">
            <div class="stack full-width">
                <label for="strasse">Straße</label>
                <input required minlength="5" type="text" id="strasse" name="strasse" placeholder="Hauptstraße"/>
            </div>
            <div class="stack full-width">
                <label for="hausnummer">Hausnummer</label>
                <input required minlength="1" type="text" id="hausnummer" name="hausnummer" placeholder="1"/>
            </div>
        </div>
        <div class="stack"><label for="plz">Postleitzahl (nicht änderbar)</label>
            <input disabled value="13158" id="plz" name="plz" type="text" />
        </div>
        <div><input required type="checkbox" name="verantwortung" id="verantwortung" aria-invalid="false"><label for="verantwortung">Ich betreibe den Hofflohmarkt auf eigene Verantwortung.</label>
        </div>
        <div><input required type="checkbox" name="dsgvo" id="dsgvo" aria-invalid="false"><label for="dsgvo">Ich bin damit einverstanden, dass meine eingegebenen Daten an die Initiative Wilhelm gibt keine Ruh gesendet werden, sowie für diesen Hofflohmarkt und Zukünftige gespeichert werden.</label>
        </div>

        <div><input type="submit" value="Anmelden" />
        </div>
    </form>
</body>