<?php
class db
{
    public $pdo;

    public function __construct()
    {
        $user = 'root';
        $pass = 'usbw';
        $dbname = 'fyp';
        $host = 'localhost';
        try {
            $this->pdo = new PDO("mysql:host=" . $host . ";dbname=" . $dbname, $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "The database is not connected " . $e;
        }
    }
}
date_default_timezone_set('Europe/London');
