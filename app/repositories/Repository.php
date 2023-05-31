<?php

require_once 'Database.php';
abstract class Repository extends Database {

    abstract protected function getTableName();

    public function __construct() {
        parent :: __construct();
    }

    public function __destruct() {
        parent :: __destruct();
    }



    public function getAll() {
        $statement = $this->pdo->prepare("SELECT * FROM {$this->getTableName()}");
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $statement = $this->pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE id = (:id)");
        $statement->execute(
            ['id' => $id]
        );

        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}