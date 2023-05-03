<?php

namespace Mail;

class Mailer
{
    private static function getHeaders()
    {
        return array(
            'From' => 'Wilhelm Gibt Keine Ruh <no-reply@wilhelm-gibt-keine-ruh.de>',
            'Reply-To' => 'no-reply@wilhelm-gibt-keine-ruh.de',
            'Content-Type' => 'text/html',
            'X-Mailer' => 'PHP/' . phpversion()
        );
    }

    public static function sendeAnmeldeBestaetigung(string $empfaengerEmail, string $absageCode)
    {

        $message = <<<EOD
            <p>Vielen Dank für Ihre Anmeldung zum Hofflohmarkt.</p>
            <p>Falls sich Ihre Pläne ändern, können Sie <a href="https://hofflohmarkt.wilhelm-gibt-keine-ruh.de/abmeldung.php?code={$absageCode}">hier</a> Ihre Teilnahme absagen.</p>
        EOD;

        mail($empfaengerEmail, 'Anmeldung Hofflohmarkt', $message, Mailer::getHeaders());
    }
}
