<?php

require_once('Repository.php');
require_once(__DIR__."/../models/UserModel.php");
class UserRepository extends Repository {


    public function __construct() {
        parent :: __construct();
    }

    protected function getTableName() {
        return 'users';
    }

    public function getById($id) {

        $user = parent :: getById($id);

        $userModel = new UserModel();
        $userModel->email = $user['email'];
        $userModel->name = $user['name'];
        $userModel->surname = $user['surname'];
        $userModel->username = $user['username'];
        $userModel->last_login_date = date('Y-m-d', strtotime($user['last_login_date']));
        $userModel->logged = $user['logged'];
        $userModel->created_at = date('Y-m-d H:i:s', strtotime($user['created_at']));
        $userModel->updated_at = date('Y-m-d H:i:s', strtotime($user['updated_at']));
        $userModel->statistics = new UserStatistics();
        $userModel->statistics->learnProgress = $this->getLearnProgress($id);
        $userModel->statistics->quizProgress = $this->getQuizProgress($id);
        $userModel->statistics->categoryStats = $this->getCategoryStats($id);
        $userModel->statistics->learnCountWeekly = $this->getLearnCountLastWeek($id);
        $userModel->statistics->quizCountWeekly = $this->getQuizCountLastWeek($id);
        $userModel->statistics->learnWeeklyStats = $this->getWeeklyLearningStats($id);
        $userModel->statistics->quizWeeklyStats = $this->getWeeklyQuizStats($id);

        return $userModel;
    }

    private function getLearnProgress($id) {
        $statement = $this->pdo->prepare("SELECT get_learn_progress(:userId)");
        $statement->execute(
            [":userId" => $id]
        );
        return $statement->fetch(PDO::FETCH_ASSOC)['get_learn_progress'];
    }


    private function getQuizProgress($id) {
        $statement = $this->pdo->prepare("SELECT get_quizzes_progress(:userId)");
        $statement->execute(
            [":userId" => $id]
        );
        return $statement->fetch(PDO::FETCH_ASSOC)['get_quizzes_progress'];
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



    private function getLearnCountLastWeek($id) {
        $statement = $this->pdo->prepare("SELECT get_user_learn_count_last_week(:userId)");
        $statement->execute(
            [":userId" => $id]
        );
        return $statement->fetch(PDO::FETCH_ASSOC)['get_user_learn_count_last_week'];
    }



    private function getQuizCountLastWeek($id) {
        $statement = $this->pdo->prepare("SELECT get_user_quiz_count_last_week(:userId)");
        $statement->execute(
            [":userId" => $id]
        );
        return $statement->fetch(PDO::FETCH_ASSOC)['get_user_quiz_count_last_week'];
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
}