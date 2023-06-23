<?php


require_once "Repository.php";
require_once __DIR__."/../models/TipModel.php";

class TipRepository extends Repository
{

    protected function getTableName(): string
    {
        return "tips";
    }

    protected function createModel(array $fetchArray): Model|null
    {
        if ( empty ( $fetchArray ) ) {
            return null;
        }

        $tipModel = new TipModel();
        $tipModel->id = $fetchArray['id'];
        $tipModel->text = $fetchArray['text'];

        return $tipModel;
    }

    public function getRandomTips() : array {

        $fetchArray = $this->pdo->query("SELECT * FROM get_random_tips()")->fetchAll(PDO::FETCH_ASSOC);

        $response = [];
        foreach ( $fetchArray as $item ) {
            $response[] = $this->createModel($item);
        }
        return $response;

    }
}