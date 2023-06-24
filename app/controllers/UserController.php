<?php

require_once ( __DIR__."/../repositories/UserRepository.php" );
require_once ( __DIR__."/../repositories/UserStatisticRepository.php" );
require_once ( __DIR__."/AuthController.php" );

class UserController extends Controller {

    public function __construct( Request $request ) {
        parent :: __construct($request);
    }

    public function login () : Response {

        $response = new Response();
        $response->setHeader ( "Content-Type", "application/json" );

        if ( ! $this->request->contentTypeIsJSON() ) {
            $response->encodeError(400, "Must provide JSON as Content-Type");
            return $response;
        }

        $json_body = json_decode($this->request->body, true);
        if ( $json_body == null ) {
            $response->encodeError(400, "Bad JSON body");
            return $response;
        }

        if ( empty ( $json_body['username'] )) {
            $response->encodeError(400, "Trebuie să dai un nume de utilizator pentru logare");
            return $response;
        }
        $username = $json_body['username'];

        if ( empty ( $json_body['password'] ) ) {
            $response->encodeError(400, "Trebuie să dai o parolă pentru logare");
            return $response;
        }
        $password = $json_body['password'];

        $userRepository = new UserRepository();
        $userModel = $userRepository->getByUsername($username);
        if ( $userModel == null ) {
            $response->encodeError(404, "Nu există niciun utilizator cu acest nume de utilizator");
        } else {
            if ( ! password_verify($password, $userModel->password ) ) {
                $response->encodeError(404, "Numele de utilizator și parola nu se potrivesc");
            } else {
                $userRepository->updateLoginStatus($userModel);
                $authController = new AuthController($this->request);
                $jwt = $authController->createJWT($userModel);
                setcookie(
                    "jwt",
                    $jwt,
                    httponly: true
                );
                $response->encodeSuccess(200, ['username' => $userModel->username] );
            }
        }
        return $response;
    }

    public function register () : Response {

        $response = new Response();
        $response->setHeader ( "Content-Type", "application/json" );

        if ( ! $this->request->contentTypeIsJSON() ) {
            $response->encodeError(400, "Must provide JSON as Content-Type");
            return $response;
        }

        $json_body = json_decode($this->request->body, true);
        if ( $json_body == null ) {
            $response->encodeError(400, "Bad JSON body");
            return $response;
        }

        if ( empty ( $json_body['email'] ) ) {
            $response->encodeError(400, "Trebuie să dai un email pentru înregistrare");
            return $response;
        }
        $email = $json_body['email'];

        if ( empty ( $json_body['name'] ) ) {
            $response->encodeError(400, "Trebuie să dai un nume pentru înregistrare");
            return $response;
        }
        $name = $json_body['name'];


        if ( empty ( $json_body['surname'] ) ) {
            $response->encodeError(400, "Trebuie să dai un prenume pentru înregistrare");
            return $response;
        }
        $surname = $json_body['surname'];


        if ( empty ( $json_body['username'] ) ) {
            $response->encodeError(400, "Trebuie să dai un nume de utilizator pentru înregistrare");
            return $response;
        }
        $username = $json_body['username'];

        if ( empty ( $json_body['password'] ) ) {
            $response->encodeError(400, "Trebuie să dai o parolă pentru înregistrare");
            return $response;
        }
        $password = $json_body['password'];
        $password = password_hash( $password, PASSWORD_DEFAULT );

        $userRepository = new UserRepository();

        $userModel = new UserModel();
        $userModel->email = $email;
        $userModel->name = $name;
        $userModel->surname = $surname;
        $userModel->username = $username;
        $userModel->password = $password;


        $status = $userRepository->create($userModel);

        if ( ! $status['ok'] ) {
            if ( $status['internal'] ) {
                $response->encodeError(500, "Internal server error");
            } else {
                $response->encodeError(400, $status["error"]);
            }
        } else {
            $response->encodeSuccess(200);
        }
        return $response;
    }


    public function getProfile () : Response {

        $response = new Response();
        $response->setHeader("Content-Type", "text/html");

        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }

        if ( $decodedToken['username'] == $this->request->pathVariables['username'] ) {
            $response->body = file_get_contents(__DIR__."/../../protected/html/ownProfile.html");
        } else {
            $response->body = file_get_contents(__DIR__."/../../protected/html/otherProfile.html");
        }

        $response->code = 200;
        return $response;
    }


    public function getResetPasswordPage () : Response {

        $response = new Response();
        $response->setHeader("Content-Type", "text/html");

        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }

        $response->body = file_get_contents(__DIR__."/../../protected/html/resetPassword.html");

        $response->code = 200;
        return $response;
    }

    public function getUserInfo () : Response {

        $response = new Response();
        $response->setHeader("Content-Type", "application/json");

        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->encodeError(401, "You are not authenticated");
            return $response;
        }

        $username = $decodedToken['username'];
        if ( isset ( $this->request->query['username'] ) ) {
            $username = $this->request->query['username'];
        }

        $userRepository = new UserRepository();
        $userModel = $userRepository->getByUsername($username);

        $userStatisticsRepository = new UserStatisticRepository();
        $userStatisticModel = $userStatisticsRepository->getById($userModel->id);
        if ( $userModel == null ) {
            $response->encodeError(404, "User does not exist");
            return $response;
        }

        if ( isset ( $this->request->query['username'] ) ) {
            $response->encodeSuccess(
                200,
                [
                    'username' => $userModel->username,
                    'statistics' => $userStatisticModel
                ]
            );
        } else {
            $dataArray = (array)$userModel;
            unset($dataArray['password']);
            $dataArray['statistics'] = $userStatisticModel;
            $response->encodeSuccess(
                200,
                    $dataArray
            );

        }
        return $response;
    }


    public function getRanking () : Response {

        $response = new Response();
        $response->setHeader("Content-Type", "application/json");

        $userRepository = new UserRepository();
        $ranking = $userRepository->getRanking();
        unset($ranking['learn_update']);
        unset($ranking['quiz_update']);
        $response->encodeSuccess(200, $ranking);

        return $response;
    }


    public function getRSSRanking () : Response {

        $response = new Response();
        $response->setHeader("Content-Type", "application/rss+xml");

        $userRepository = new UserRepository();
        $data = $userRepository->getRanking();

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom"></rss>');
        $channel = $xml->addChild('channel');
        $channel->addChild('title', 'ROT ranking feed');
        $channel->addChild('description', 'The top users of Romanian traffic signs tutor');
        $channel->addChild('link', "http://localhost/public/ranking_rss");

        $learnDateTime = new DateTime($data['learn_update']);

        foreach ( $data['learn'] as $user ) {
            $userItem = $channel->addChild('item');
            $userItem->addChild('description', 'The number of questions learned by the user '.$user['username'].' is '.$user['result']);
            $userItem->addChild('pubDate', $learnDateTime->format(DateTimeInterface::RFC822));
            $userItem->addChild ('guid', "learn#".$user['username']."#")->addAttribute ("isPermaLink", "false");
        }

        $quizDateTime = new DateTime($data['quiz_update']);

        foreach ( $data['quiz'] as $user ) {
            $userItem = $channel->addChild('item');
            $userItem->addChild('description', 'The number of quizzes solved by the user '.$user['username'].' is '.$user['result']);
            $userItem->addChild('pubDate', $quizDateTime->format(DateTimeInterface::RFC822) );
            $userItem->addChild ('guid', "quiz#".$user['username']."#")->addAttribute ("isPermaLink", "false");
        }

        $response->code = 200;
        $response->body = $xml->asXML();

        return $response;
    }

    public function getAllUsers () : Response {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");

        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->encodeError("401", "You are not authorized.");
            return $response;
        }

        $userRepository = new UserRepository();
        if ( ! $userRepository->checkAdmin($decodedToken['id'] ) ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }

        $response->encodeSuccess(200, $userRepository->getAll());

        return $response;
    }

    public function deleteUser () : Response {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");

        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->encodeError("401", "You are not authorized.");
            return $response;
        }

        $userRepository = new UserRepository();
        if ( ! $userRepository->checkAdmin($decodedToken['id'] ) ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }

        $userRepository->deleteUser($this->request->pathVariables['userId']);
        $response->encodeSuccess(200);
        return $response;
    }


    public function recoverPassword () : Response {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");



        if ( ! $this->request->contentTypeIsJSON() ) {
            $response->encodeError(400, "Must provide JSON as Content-Type");
            return $response;
        }

        $json_body = json_decode($this->request->body, true);
        if ( $json_body == null ) {
            $response->encodeError(400, "Bad JSON body");
            return $response;
        }

        if ( empty ( $json_body['email'] ) ) {
            $response->encodeError(400, "Must provide email for recovery");
            return $response;
        }

        $userRepository = new UserRepository();
        $userModel = $userRepository->getByEmail ($json_body['email']);
        if ( $userModel == null ) {
            $response->encodeError (400, "Nu există niciun user cu acest email");
            return $response;
        }
        $userModel->password = password_hash( $userModel->username, PASSWORD_DEFAULT );
        $userRepository->updatePassword ($userModel);
        $response->encodeSuccess (200);
        return $response;
    }


    public function resetPassword () : Response {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");

        if ( ! $this->request->contentTypeIsJSON() ) {
            $response->encodeError(400, "Must provide JSON as Content-Type");
            return $response;
        }

        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->encodeError(401, "You are not authenticated");
            return $response;
        }

        $json_body = json_decode($this->request->body, true);
        if ( $json_body == null ) {
            $response->encodeError(400, "Bad JSON body");
            return $response;
        }

        if ( empty ( $json_body['oldPassword'] ) ) {
            $response->encodeError(400, "Must provide old password for recovery");
            return $response;
        }

        if ( empty ( $json_body['newPassword'] ) ) {
            $response->encodeError(400, "Must provide new password for recovery");
            return $response;
        }

        $userRepository = new UserRepository();
        $userModel = $userRepository->getById ($decodedToken['id']);
        if ( $userModel == null ) {
            $response->encodeError (400, "Nu există niciun user cu acest email");
            return $response;
        }

        if ( ! password_verify ($json_body['oldPassword'], $userModel->password ) ) {
            $response->encodeError (400, "The old password does not match");
            return $response;
        }

        $userModel->password = password_hash( $json_body['newPassword'], PASSWORD_DEFAULT );
        $userRepository->updatePassword ($userModel);
        $response->encodeSuccess (200);
        return $response;
    }


    public function logout () : Response {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");

        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->encodeError(401, "You are not authenticated");
            return $response;
        }

        if ( isset ( $_COOKIE['jwt'] ) ) {
            unset ( $_COOKIE[ 'jwt' ] );
            setcookie(
                "jwt",
                '',
                1,
                httponly: true
            );
        }
        $response->encodeSuccess (200 );
        return $response;
    }
}