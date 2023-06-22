<?php

class AnswerRepository extends Repository
{

    protected function getTableName(): string
    {
        return "answers";
    }

    protected function createModel(array $fetchArray): Model|null
    {
        if ( empty ( $fetchArray ) ) {
            return null;
        }

        $answerModel = new AnswerModel();
        $answerModel->id = $fetchArray['id'];
        $answerModel->text = $fetchArray['text'];

        return $answerModel;
    }


    public function getByText($text) : Model | null {
        $statement = $this->pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE text = (:text)");
        try {
            $statement->execute(
                ['text' => $text]
            );
        } catch ( PDOException ) {
            return null;
        }

        $fetchArray = $statement->fetch(PDO::FETCH_ASSOC);
        if ( ! $fetchArray ) {
            return null;
        }
        return $this->createModel($fetchArray );
    }

    public function create(AnswerModel $answerModel) : void {
        $statement = $this->pdo->prepare("INSERT INTO answers(text) VALUES(:text)");
        $statement->execute(
            ['text' => $answerModel->text]
        );
        $answerModel->id = $this->pdo->lastInsertId();
    }
}