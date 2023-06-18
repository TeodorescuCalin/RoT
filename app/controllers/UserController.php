<?php

require_once ( __DIR__."/../repositories/UserRepository.php" );
require_once ( __DIR__."/AuthController.php" );

class UserController {

    public function __construct() {

    }

    public function login ( Request $request ) : Response {

        $response = new Response();
        $response->setHeader ( "Content-Type", "application/json" );

        if ( ! $request->contentTypeIsJSON() ) {
            $response->encodeError(400, "Must provide JSON as Content-Type");
            return $response;
        }

        $json_body = json_decode($request->body, true);
        if ( $json_body == null ) {
            $response->encodeError(400, "Bad JSON body");
            return $response;
        }

        if ( ! isset ( $json_body['username'] ) ) {
            $response->encodeError(400, "Must provide username for login");
            return $response;
        }
        $username = $json_body['username'];

        if ( ! isset ( $json_body['password'] ) ) {
            $response->encodeError(400, "Must provide password for login");
            return $response;
        }
        $password = $json_body['password'];

        $userRepository = new UserRepository();
        $status = $userRepository->login($username);

        if ( ! $status['ok'] ) {
            $response->encodeError(500, "Internal server error");
        } else {
            $user = $status["user"];
            if ( $user == null ) {
                $response->encodeError(404, "User with given username does not exist");
            } else {
                if ( ! password_verify($password, $user->password ) ) {
                    $response->encodeError(404, "Username and password do not match");
                } else {
                    $authController = new AuthController();
                    $response->encodeSuccess(200, ["token" => $authController->createJWT($user) ] );
                }
            }
        }
        return $response;
    }

    public function signup ( Request $request ) : Response {

        $response = new Response();
        $response->setHeader ( "Content-Type", "application/json" );

        if ( ! $request->contentTypeIsJSON() ) {
            $response->encodeError(400, "Must provide JSON as Content-Type");
            return $response;
        }

        $json_body = json_decode($request->body, true);
        if ( $json_body == null ) {
            $response->encodeError(400, "Bad JSON body");
            return $response;
        }

        if ( ! isset ( $json_body['email'] ) ) {
            $response->encodeError(400, "Must provide email for signup");
            return $response;
        }
        $email = $json_body['email'];

        if ( ! isset ( $json_body['name'] ) ) {
            $response->encodeError(400, "Must provide name for signup");
            return $response;
        }
        $name = $json_body['name'];


        if ( ! isset ( $json_body['surname'] ) ) {
            $response->encodeError(400, "Must provide surname for signup");
            return $response;
        }
        $surname = $json_body['surname'];


        if ( ! isset ( $json_body['username'] ) ) {
            $response->encodeError(400, "Must provide username for signup");
            return $response;
        }
        $username = $json_body['username'];

        if ( ! isset ( $json_body['password'] ) ) {
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
}