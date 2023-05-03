<?php
namespace Environment;

// Hier nur für die lokale Testumgebung die Zugänge verwalten
class Environment {
    public static array $dbSettings = array('host' => 'mysql', 'db' => 'd03ce714', 'user' => 'root', 'password' => 'totallyunsafe');
}