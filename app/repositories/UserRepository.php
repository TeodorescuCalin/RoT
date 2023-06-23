<?php

require_once('Repository.php');
require_once(__DIR__."/../models/UserModel.php");
class UserRepository extends Repository {


    public function __construct() {
        parent :: __construct();
    }

    protected function getTableName() : string {
        return 'users';
    }

    protected function createModel( array $fetchArray ) : UserModel | null
    {
        if ( empty ( $fetchArray ) ) {
            return null;
        }

        $userModel = new UserModel();
        $userModel->id = $fetchArray['id'];
        $userModel->email = $fetchArray['email'];
        $userModel->name = $fetchArray['name'];
        $userModel->surname = $fetchArray['surname'];
        $userModel->username = $fetchArray['username'];
        $userModel->password = $fetchArray['password'];
        $userModel->last_login_date = date('Y-m-d', strtotime($fetchArray['last_login_date']));
        $userModel->created_at = date('Y-m-d H:i:s', strtotime($fetchArray['created_at']));
        $userModel->updated_at = date('Y-m-d H:i:s', strtotime($fetchArray['updated_at']));

        return $userModel;
    }


    public function create ( UserModel $userModel ) : array {

        $email = $userModel->email;
        $name = $userModel->name;
        $surname = $userModel->surname;
        $username = $userModel->username;
        $password = $userModel->password;

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
                        "internal" => false,
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


    public function getByUsername ( string $username ) : UserModel | null {
        $statement = $this->pdo->prepare("SELECT * FROM users WHERE username=:username");
        try {
            $statement->execute(
                [
                    ":username" => $username
                ]
            );
        } catch ( PDOException ) {
            return null;
        }

        $fetchArray = $statement->fetch(PDO::FETCH_ASSOC);

        if ( ! $fetchArray ) {
            return null;
        }

        return $this->createModel($fetchArray);
    }


    public function updateLoginStatus ( UserModel $userModel ) : void {
        $this->pdo->exec("UPDATE users SET last_login_date=current_date WHERE id=".$userModel->id);
    }


    public function checkAdmin ( $userId ) : bool {
        $statement = $this->pdo->prepare("SELECT * FROM users WHERE id=(:userId) AND admin=true");
        $statement->execute(
            [
                "userId" => $userId
            ]
        );
        if ( ! $statement->fetch() ) {
            return false;
        }

        return true;
    }


    public function getRanking () : array {

        $result = [];
        $result['learn'] = $this->pdo->query("SELECT * FROM get_learn_users_ranking()" )->fetchAll(PDO::FETCH_ASSOC);
        $result['quiz'] = $this->pdo->query("SELECT * FROM get_quiz_users_ranking()" )->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}