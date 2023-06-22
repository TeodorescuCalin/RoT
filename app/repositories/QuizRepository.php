<?php

class QuizRepository extends Repository
{

    protected function getTableName(): string
    {
        return "quiz";
    }

    protected function createModel(array $fetchArray): Model|null
    {
        // TODO: Implement createModel() method.
    }

    public function getAll() : array {
        $statement = $this->pdo->prepare("SELECT * FROM {$this->getTableName()}");
        try {
            $statement->execute();
        } catch ( PDOException ) {
            return [];
        }

        $resultArray = [];
        foreach ( $statement->fetchAll(PDO::FETCH_ASSOC) as $fetchArray ) {
            $answersStatement = $this->pdo->prepare("SELECT answers.id, answers.text, qqa.correct 
                FROM answers 
                    JOIN quiz_questions_answers qqa ON answers.id = qqa.id_answer 
                WHERE la.id_question = (:id_question)" );
            $answersStatement->execute(
                ['id_question' => $fetchArray['id']]
            );
            $fetchArray['answers'] = $answersStatement->fetchAll(PDO::FETCH_ASSOC);

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

        $answersStatement = $this->pdo->prepare("SELECT answers.id, answers.text, la.count 
                FROM answers 
                    JOIN learn_questions_answers la ON answers.id = la.id_answer 
                WHERE la.id_question = (:id_question)" );
        $answersStatement->execute(
            ['id_question' => $id]
        );
        $fetchArray['answers'] = $answersStatement->fetchAll(PDO::FETCH_ASSOC);

        return $this->createModel($fetchArray);
    }
}