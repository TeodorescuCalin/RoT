<?php

require_once('Repository.php');
require_once(__DIR__."/../models/LearnQuestionModel.php");
require_once(__DIR__."/../models/UserLearnQuestionModel.php");
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
        $learnQuestionModel->explanation = $fetchArray['explanation'];
        $learnQuestionModel->category = $fetchArray['category'];
        $learnQuestionModel->answer_count = $fetchArray['answer_count'];

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
            $answersStatement = $this->pdo->prepare("SELECT answers.id, answers.text, la.count 
                FROM answers 
                    JOIN learn_questions_answers la ON answers.id = la.id_answer 
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

        $fetchValues = $answersStatement->fetchAll(PDO::FETCH_ASSOC);
        if ( ! $fetchValues ) {
            return null;
        }

        $fetchArray['answers'] = $fetchValues;

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


    public function getStatusForUser($questionId, $userId) : UserLearnQuestionModel | null {

        $statement = $this->pdo->prepare("SELECT status FROM user_learn_questions WHERE id_question=(:questionId) AND id_user=(:userId)");
        try {
            $statement->execute(
                [
                    'questionId' => $questionId,
                    'userId' => $userId
                ]
            );
        } catch ( PDOException ) {
            return null;
        }

        $fetchArray = $statement->fetch(PDO::FETCH_ASSOC);
        if ( ! $fetchArray ) {
            return null;
        }

        $userQuestionModel = new UserLearnQuestionModel();
        $userQuestionModel->status = $fetchArray['status'];
        $userQuestionModel->userId = $userId;
        $userQuestionModel->questionId = $questionId;
        return $userQuestionModel;
    }


    public function updateQuestionStatus(UserLearnQuestionModel $userModel) : bool {
        $statement = $this->pdo->prepare("UPDATE user_learn_questions SET status=(:status) WHERE id_question=(:questionId) AND id_user=(:userId)");
        try {
            $statement->execute(
                [
                    'status' => $userModel->status,
                    'questionId' => $userModel->questionId,
                    'userId' => $userModel->userId
                ]
            );
        } catch ( PDOException ) {
            return false;
        }

        if ( $statement->rowCount() == 0 ) {
            return false;
        }
        return true;
    }

    public function createQuestionStatus(UserLearnQuestionModel $userModel) : bool {
        $statement = $this->pdo->prepare("INSERT INTO user_learn_questions(id_user, id_question, status ) VALUES ((:userId), (:questionId), (:status) )");
        try {
            $statement->execute(
                [
                    'status' => $userModel->status,
                    'questionId' => $userModel->questionId,
                    'userId' => $userModel->userId
                ]
            );
        } catch ( PDOException ) {
            return false;
        }

        return true;
    }

    public function create(LearnQuestionModel $questionModel) : void {
        $statement = $this->pdo->prepare("INSERT INTO learn_questions(text, image_path, explanation, type, category, answer_count) VALUES((:text), (:image_path), (:explanation), (:type), (:category), (:answer_count))");
        try {
            $statement->execute(
                [
                    'text' => $questionModel->text,
                    'image_path' => $questionModel->image_path,
                    'explanation' => $questionModel->explanation,
                    'type' => $questionModel->type,
                    'category' => $questionModel->category,
                    'answer_count' => $questionModel->answer_count
                ]
            );
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $questionModel->id = $this->pdo->lastInsertId();

        foreach ( $questionModel->answers as $answer ) {
            $statement = $this->pdo->prepare("INSERT INTO learn_questions_answers VALUES ((:questionId), (:answerId), (:count), (:category))");
            try {
                $statement->execute(
                    [
                        'questionId' => $questionModel->id,
                        'answerId' => $answer['id'],
                        'category' => $questionModel->category,
                        'count' => $answer['count']
                    ]
                );
            } catch ( PDOException $e ) {
                echo $e->getMessage();
            }
        }
    }
}