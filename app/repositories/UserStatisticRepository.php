<?php

require_once('Repository.php');
require_once(__DIR__."/../models/UserStatisticsModel.php");

class UserStatisticRepository extends Repository {

    public function __construct(){
        parent::__construct();
    }

    protected function getTableName() : string {
        return "";
    }



    protected function createModel(array $fetchArray): Model|null
    {
        $userStatisticsModel = new UserStatisticsModel();
        $userStatisticsModel->learnProgress = $fetchArray['get_learn_progress'];
        $userStatisticsModel->quizProgress = $fetchArray['get_quizzes_progress'];
        $userStatisticsModel->learnCountWeekly = $fetchArray['get_user_learn_count_last_week'];
        $userStatisticsModel->quizCountWeekly = $fetchArray['get_user_quiz_count_last_week'];

        $userStatisticsModel->categoryStats = $this->getCategoryStats($fetchArray['id']);
        $userStatisticsModel->learnWeeklyStats = $this->getWeeklyLearningStats($fetchArray['id']);
        $userStatisticsModel->quizWeeklyStats = $this->getWeeklyQuizStats($fetchArray['id']);
        $userStatisticsModel->learnStatistics = $this->getLearnStatistics($fetchArray['id']);
        $userStatisticsModel->quizStatistics = $this->getQuizStatistics($fetchArray['id']);
        $userStatisticsModel->quizDurations = $this->getQuizDurations($fetchArray['id']);

        return $userStatisticsModel;
    }

    public function getById($id): Model|null
    {
        $userExistsStmt = $this->pdo->prepare("SELECT * FROM users WHERE id=(:userId)");
        try {
            $userExistsStmt->execute(
                ['userId' => $id ]
            );
        } catch ( PDOException ) {
            return null;
        }
        if ( ! $userExistsStmt->fetch() ) {
            return null;
        }

        $statement = $this->pdo->prepare(
            "SELECT ".$id." AS id,
                get_learn_progress(:userId),
                get_quizzes_progress(:userId),
                get_user_learn_count_last_week(:userId),
                get_user_quiz_count_last_week(:userId)"
        );

        try {
            $statement->execute(
                [":userId" => $id ]
            );
        } catch ( PDOException $e ){
            echo $e->getMessage();
            return null;
        }
        return $this->createModel($statement->fetch(PDO::FETCH_ASSOC));
    }

    private function getCategoryStats($id) :array {
        $statement = $this->pdo->prepare("SELECT * FROM get_stats_on_all_categories(:userId)");
        $statement->execute(
            [":userId" => $id]
        );

        $rezArray = [];
        foreach ( $statement->fetchAll(PDO::FETCH_ASSOC) as $rez ) {
            $rezArray[] = $rez;
        }
        return $rezArray;
    }

    private function getWeeklyLearningStats($id) :array {
        $statement = $this->pdo->prepare("SELECT * FROM get_learn_question_stats_by_week(:userId)");
        $statement->execute(
            [":userId" => $id]
        );

        $rezArray = [];
        foreach ( $statement->fetchAll(PDO::FETCH_ASSOC) as $rez ) {
            $rezArray[] = $rez;
        }
        return $rezArray;
    }


    private function getWeeklyQuizStats($id) :array {
        $statement = $this->pdo->prepare("SELECT * FROM get_quiz_stats_by_week(:userId)");
        $statement->execute(
            [":userId" => $id]
        );

        $rezArray = [];
        foreach ( $statement->fetchAll(PDO::FETCH_ASSOC) as $rez ) {
            $rezArray[] = $rez;
        }
        return $rezArray;
    }

    private function getQuizStatistics($id) :UserQuizStatistics {
        $statement = $this->pdo->prepare("SELECT * FROM get_user_quiz_statistics(:userId)");
        $statement->execute(
            [":userId" => $id]
        );

        $fetchArray = $statement->fetch(PDO::FETCH_ASSOC);
        $userQuizStatistics = new UserQuizStatistics();
        $userQuizStatistics->total = $fetchArray['total'];
        $userQuizStatistics->failed = $fetchArray['failed'];
        $userQuizStatistics->passed = $fetchArray['passed'];
        $userQuizStatistics->perfect = $fetchArray['perfect'];
        $userQuizStatistics->average_duration = $fetchArray['average_duration'];
        $userQuizStatistics->shortest_duration = $fetchArray['shortest_duration'];
        $userQuizStatistics->longest_duration = $fetchArray['longest_duration'];
        return $userQuizStatistics;
    }

    private function getLearnStatistics($id) :UserLearnStatistics {
        $statement = $this->pdo->prepare("SELECT * FROM get_user_learn_statistics(:userId)");
        $statement->execute(
            [":userId" => $id]
        );

        $fetchArray = $statement->fetch(PDO::FETCH_ASSOC);
        $userQuizStatistics = new UserLearnStatistics();
        $userQuizStatistics->total = $fetchArray['total'];
        $userQuizStatistics->failed = $fetchArray['failed'];
        $userQuizStatistics->passed = $fetchArray['passed'];
        return $userQuizStatistics;
    }

    private function getQuizDurations($id) :array {
        $statement = $this->pdo->prepare("SELECT * FROM get_user_last_quizzes_times(:userId)");
        $statement->execute(
            [":userId" => $id]
        );

        $result = [];
        foreach ( $statement->fetchAll(PDO::FETCH_ASSOC) as $item ) {
            $result[] = $item['get_user_last_quizzes_times'];
        }

        return $result;
    }
}