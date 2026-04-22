<?php

class Database
{
    private static ?PDO $instance = null;

    private string $host   = 'localhost';
    private string $dbName = 'student_management';
    private string $user   = 'root';
    private string $pass   = '';          // change to your MySQL password
    private string $port   = '3306';

    private function __construct() {}

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $obj = new self();
            $dsn = "mysql:host={$obj->host};port={$obj->port};dbname={$obj->dbName};charset=utf8mb4";
            self::$instance = new PDO($dsn, $obj->user, $obj->pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }
        return self::$instance;
    }
}
