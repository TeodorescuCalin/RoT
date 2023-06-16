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

    protected function createModel( array $fetchArray ) : UserModel | null
    {
        if ( empty ( $fetchArray ) ) {
            return null;
        }

        $id = $fetchArray['id'];

        $userModel = new UserModel();
        $userModel->id = $id;
        $userModel->email = $fetchArray['email'];
        $userModel->name = $fetchArray['name'];
        $userModel->surname = $fetchArray['surname'];
        $userModel->username = $fetchArray['username'];
        $userModel->password = $fetchArray['password'];
        $userModel->last_login_date = date('Y-m-d', strtotime($fetchArray['last_login_date']));
        $userModel->created_at = date('Y-m-d H:i:s', strtotime($fetchArray['created_at']));
        $userModel->updated_at = date('Y-m-d H:i:s', strtotime($fetchArray['updated_at']));
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


    public function signup ( string $email, string $name, string $surname, string $username, string $password ) : array {
        $statement = $this->pdo->prepare(
            "INSERT INTO users (email, name, surname, username, password ) ".
                        "VALUES (:email, :name, :surname, :username, :password)");
        try {
            $statement->execute(
                [
                    ":email" => $email,
                    ":name" => $name,
                    ":surname" => $surname,
                    ":username" => $username,
                    ":password" => $password
                ]
            );
        } catch ( PDOException $exception ) {
            if ( $exception->getCode() == 23505 ) {
                if ( str_contains ( $exception->getMessage(), "email") ) {
                    return [
                        "ok" => false,
                        "error" => "The email already exists"
                    ];
                } else {
                    if ( str_contains ( $exception->getMessage(), "username") ) {
                        return [
                            "ok" => false,
                            "internal" => false,
                            "error" => "The username already exists"
                        ];
                    }
                }
            }
            return [
                "ok" => false,
                "internal" => true
            ];
        }

        return ["ok" => true];
    }


    public function login ( string $username) : array {
        $statement = $this->pdo->prepare("SELECT * FROM users WHERE username=:username");
        try {
            $statement->execute(
                [
                    ":username" => $username
                ]
            );
        } catch ( PDOException $exception ) {
            return [
                "ok" => false,
                "error" => "Internal server error"
            ];
        }
        $fetchArray = $statement->fetch(PDO::FETCH_ASSOC);
        if ( ! $fetchArray ) {
            return [
                "ok" => true,
                "user" => null
            ];
        }

        return [
            "ok" => true,
            "user" => $this->createModel($fetchArray)
        ];
    }
}