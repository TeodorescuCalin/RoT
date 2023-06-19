<?php

require_once ( __DIR__."/../repositories/UserRepository.php" );
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
            $response->encodeError(400, "Must provide username for login");
            return $response;
        }
        $username = $json_body['username'];

        if ( empty ( $json_body['password'] ) ) {
            $response->encodeError(400, "Must provide password for login");
            return $response;
        }
        $password = $json_body['password'];

        $userRepository = new UserRepository();
        $userModel = $userRepository->getByUsername($username);
        if ( $userModel == null ) {
            $response->encodeError(404, "User with given username does not exist");
        } else {
            if ( ! password_verify($password, $userModel->password ) ) {
                $response->encodeError(404, "Username and password do not match");
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

    public function signup () : Response {

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
            $response->encodeError(400, "Must provide email for signup");
            return $response;
        }
        $email = $json_body['email'];

        if ( empty ( $json_body['name'] ) ) {
            $response->encodeError(400, "Must provide name for signup");
            return $response;
        }
        $name = $json_body['name'];


        if ( empty ( $json_body['surname'] ) ) {
            $response->encodeError(400, "Must provide surname for signup");
            return $response;
        }
        $surname = $json_body['surname'];


        if ( empty ( $json_body['username'] ) ) {
            $response->encodeError(400, "Must provide username for signup");
            return $response;
        }
        $username = $json_body['username'];

        if ( empty ( $json_body['password'] ) ) {
            $response->encodeError(400, "Must provide password for signup");
            return $response;
        }
        $password = $json_body['password'];
        $password = password_hash( $password, PASSWORD_DEFAULT );

        $userRepository = new UserRepository();
        $status = $userRepository->signup($email, $name, $surname, $username, $password);

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
        if ( $userModel == null ) {
            $response->encodeError(404, "User does not exist");
            return $response;
        }

        if ( isset ( $this->request->query['username'] ) ) {
            $response->encodeSuccess(
                200,
                [
                    'username' => $userModel->username,
                    'statistics' => $userModel->statistics
                ]
            );
        } else {
            $dataArray = (array)$userModel;
            unset($dataArray['password']);
            $response->encodeSuccess(
                200,
                    $dataArray
            );

        }
        return $response;
    }
}