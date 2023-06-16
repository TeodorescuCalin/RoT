<?php

require_once 'Database.php';
abstract class Repository extends Database {

    abstract protected function getTableName();

    public function __construct() {
        parent :: __construct();
    }

    protected abstract function createModel ( array $fetchArray ) : Model | null;

    public function getAll() : array {
        $statement = $this->pdo->prepare("SELECT * FROM {$this->getTableName()}");
        $statement->execute();

        $resultArray = [];
        foreach ( $statement->fetchAll(PDO::FETCH_ASSOC) as $fetchArray ) {
            $resultArray[] = $this->createModel($fetchArray);
        }

        return $resultArray;
    }

    public function getById($id) : Model | null {
        $statement = $this->pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE id = (:id)");
        $statement->execute(
            ['id' => $id]
        );

        return $this->createModel( $statement->fetch(PDO::FETCH_ASSOC) );
    }
}