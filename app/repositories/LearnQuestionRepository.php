<?php

require_once('Repository.php');
require_once(__DIR__."/../models/LearnQuestionModel.php");
class LearnQuestionRepository extends Repository
{

    protected function getTableName(): string
    {
        return "learn_questions";
    }

    protected function createModel(array $fetchArray): Model|null
    {
        if ( empty ( $fetchArray ) ) {
            return null;
        }

        $learnQuestionModel = new LearnQuestionModel();
        $learnQuestionModel->id = $fetchArray['id'];
        $learnQuestionModel->text = $fetchArray['text'];
        $learnQuestionModel->image_path = $fetchArray['image_path'];
        $learnQuestionModel->type = $fetchArray['type'];
        $learnQuestionModel->answers = $fetchArray['answers'];

        return $learnQuestionModel;
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
            $resultArray[] = $this->createModel($fetchArray);
        }
        //TODO implement answer list for this
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

        $answersStatement = $this->pdo->prepare("SELECT answers.id, answers.text 
                FROM answers 
                    JOIN learn_questions_answers la ON answers.id = la.id_answer 
                WHERE la.id_question = (:id_question)" );
        $answersStatement->execute(
            ['id_question' => $id]
        );
        $fetchArray['answers'] = $answersStatement->fetchAll(PDO::FETCH_OBJ);

        return $this->createModel($fetchArray);
    }


    public function getForUser($userId) : Model | null {

        $statement = $this->pdo->prepare("SELECT * FROM get_user_learning_question(:userId)");
        try {
            $statement->execute(
                [
                    "userId" => $userId
                ]
            );
        } catch ( PDOException ) {
            return null;
        }

        $questionId = $statement->fetch(PDO::FETCH_ASSOC)['get_user_learning_question'];
        $questionModel = $this->getById($questionId);

        $answerStatement = $this->pdo->prepare("SELECT * FROM get_wrong_answers_for_learn_question(:questionId)");
        $answerStatement->execute(
            [
                "questionId" => $questionId
            ]
        );

        $questionModel->answers = array_merge($questionModel->answers, $answerStatement->fetchAll(PDO::FETCH_ASSOC));
        return $questionModel;
    }
}