<?php

require_once( __DIR__.'/../config/config.php');

class Database {
    protected PDO $pdo;

    function __construct() {
        $this->openDatabaseConnection();
    }

    function __destruct() {
        $this->closeDatabaseConnection();
    }

    private function openDatabaseConnection() : void {
        $dsn = DB_TYPE
            .":host=".DB_HOST
            .";port=".DB_PORT
            .";dbname=".DB_NAME;

        $this->pdo = new PDO(
            $dsn,
            DB_USER,
            DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }


    private function closeDatabaseConnection() {
    }
}