<?php

require_once "Repository.php";
require_once __DIR__."/../models/QuizModel.php";
require_once __DIR__."/../models/UserQuizModel.php";
require_once __DIR__."/../models/QuizQuestionModel.php";
class QuizRepository extends Repository
{

    protected function getTableName(): string
    {
        return "quiz";
    }

    protected function createModel(array $fetchArray): Model|null
    {
        if ( empty ( $fetchArray ) ) {
            return null;
        }

        $quizModel = new QuizModel();
        $quizModel->id = $fetchArray['id'];
        $quizModel->questions = $fetchArray['questions'];

        return $quizModel;
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

            $questionsStatement = $this->pdo->prepare("SELECT DISTINCT id_question FROM quiz_questions_answers WHERE id_quiz = (:id_quiz)" );
            $questionsStatement->execute(
                ['id_quiz' => $fetchArray['id']]
            );
            $fetchArray['questions'] = [];
            foreach ( $questionsStatement->fetchAll(PDO::FETCH_ASSOC) as $question ) {
                $fetchArray['questions'][] = $this->getQuizQuestion($fetchArray['id'], $question['id']);
            }

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

        $questionsStatement = $this->pdo->prepare("SELECT DISTINCT id_question FROM quiz_questions_answers WHERE id_quiz = (:id_quiz)" );
        $questionsStatement->execute(
            ['id_quiz' => $id]
        );
        $fetchArray['questions'] = [];
        foreach ( $questionsStatement->fetchAll(PDO::FETCH_ASSOC) as $question ) {
            $fetchArray['questions'][] = $this->getQuizQuestion($id, $question['id_question']);
        }

        return $this->createModel($fetchArray);
    }

    public function getQuizQuestion($quizId, $questionId) : QuizQuestionModel | null {
        $statement = $this->pdo->prepare("SELECT * FROM get_quiz_question(:quizId, :questionId)");
        $statement->execute(
            [
                'quizId' => $quizId,
                'questionId' => $questionId
            ]
        );

        $fetchArray = $statement->fetch(PDO::FETCH_ASSOC);

        if ( ! $fetchArray ) {
            return null;
        }

        $quizQuestionModel = new QuizQuestionModel();
        $quizQuestionModel->id = $fetchArray['id'];
        $quizQuestionModel->text = $fetchArray['text'];
        $quizQuestionModel->image_path = $fetchArray['image_path'];
        $quizQuestionModel->first_answer_id = $fetchArray['first_answer_id'];
        $quizQuestionModel->second_answer_id = $fetchArray['second_answer_id'];
        $quizQuestionModel->third_answer_id = $fetchArray['third_answer_id'];
        $quizQuestionModel->first_answer_correct = $fetchArray['first_answer_correct'];
        $quizQuestionModel->second_answer_correct = $fetchArray['second_answer_correct'];
        $quizQuestionModel->third_answer_correct = $fetchArray['third_answer_correct'];
        $quizQuestionModel->first_answer_text = $fetchArray['first_answer_text'];
        $quizQuestionModel->second_answer_text = $fetchArray['second_answer_text'];
        $quizQuestionModel->third_answer_text = $fetchArray['third_answer_text'];

        return $quizQuestionModel;
    }


    public function getAllQuizzesForUser ($userId) : array {
        $statement = $this->pdo->prepare("SELECT id, status, duration, correct_answers FROM quiz LEFT OUTER JOIN user_quizzes uq on quiz.id = uq.id_quiz WHERE uq.id_user=(:userId) OR uq.id_user IS NULL ORDER BY id");
        $statement->execute(
            [
                'userId' => $userId
            ]
        );
        $fetchArray = $statement->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ( $fetchArray as $item ) {
            $userQuizModel = new UserQuizModel();
            $userQuizModel->userId = $userId;
            $userQuizModel->quizId = $item['id'];
            $userQuizModel->status = $item['status'];
            $userQuizModel->correctAnswerCount = $item['correct_answers'];
            $userQuizModel->duration = $item['duration'];

            $result[] = $userQuizModel;
        }

        return $result;
    }
}