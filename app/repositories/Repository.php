<?php

require_once 'Database.php';
abstract class Repository extends Database {

    abstract protected function getTableName() : string;

    public function __construct() {
        parent :: __construct();
    }

    protected abstract function createModel ( array $fetchArray ) : Model | null;

    public function getAll() : array {
        $statement = $this->pdo->prepare("SELECT * FROM {$this->getTableName()}");
        try {
            $statement->execute();
        } catch ( PDOException ) {
            return [];
        }

        $resultArray = [];
        foreach ( $statement->fetchAll(PDO::FETCH_ASSOC) as $fetchArray ) {
            $resultArray[] = $this->createModel($fetchArray);
        }

        return $resultArray;
    }

    public function getById($id) : Model | null {
        $statement = $this->pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE id = (:id)");
        try {
            $statement->execute(
                ['id' => $id]
            );
        } catch ( PDOException ) {
            return null;
        }

        $fetchArray = $statement->fetch(PDO::FETCH_ASSOC);
        if ( ! $fetchArray ) {
            return null;
        }

        return $this->createModel( $statement->fetch($fetchArray ) );
    }
}