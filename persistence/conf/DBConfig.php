<?php

class DBConfig
{
    const DB_HOST = 'localhost';
    const DB_USER = 'root';
    const DB_PASS = '';
    const DB_NAME = '4vgym';

    private static $connection = null;


    public static function getConnection()
    {

        self::$connection = new mysqli(
            self::DB_HOST,
            self::DB_USER,
            self::DB_PASS,
            self::DB_NAME
        );

        return self::$connection;
    }

    public static function closeConnection()
    {
        if (self::$connection !== null) {
            self::$connection->close();
            self::$connection = null;
        }
    }
}
?>