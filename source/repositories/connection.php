<?php
declare(strict_types=1);
namespace repositories;

use Environment\Environment;
use Exception;



final class Connection
{

    private static ?\PDO $instance = null;


    public static function getDefaultConnection(): \PDO
    {

        if (self::$instance === null) {
            $dbSettings = Environment::$dbSettings;
            self::$instance = new \PDO("mysql:dbname={$dbSettings['db']};host={$dbSettings['host']}", $dbSettings['user'], $dbSettings['password'], array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        }


        return self::$instance;
    }


    /**

     * is not allowed to call from outside to prevent from creating multiple instances,

     * to use the singleton, you have to obtain the instance from Singleton::getInstance() instead

     */

    private function __construct()

    {

    }


    /**

     * prevent the instance from being cloned (which would create a second instance of it)

     */

    private function __clone()

    {

    }


    /**

     * prevent from being unserialized (which would create a second instance of it)

     */

    public function __wakeup()

    {

        throw new Exception("Cannot unserialize singleton");

    }

}