<?php

require_once ( __DIR__."/../repositories/UserRepository.php" );

class UserController {

    public function __construct() {

    }

    public function login ( Request $request ) : Response {

        $response = new Response();
        $response->setHeader ( "Content-Type", "application/json" );

        if ( ! $request->contentTypeIsJSON() ) {
            $response->code = 400;
            $response->body = json_encode ( ["error" => "Must provide JSON as Content-Type"] );
            return $response;
        }

        $json_body = json_decode($request->body, true);
        if ( $json_body == null ) {
            $response->code = 400;
            $response->body = json_encode ( ["error" => "Bad JSON body"] );
            return $response;
        }

        if ( ! isset ( $json_body['username'] ) ) {
            $response->code = 400;
            $response->body = json_encode ( ["error" => "Must provide username for login"] );
            return $response;
        }
        $username = $json_body['username'];

        if ( ! isset ( $json_body['password'] ) ) {
            $response->code = 400;
            $response->body = json_encode ( ["error" => "Must provide password for login"] );
            return $response;
        }
        $password = $json_body['password'];
        $userRepository = new UserRepository();
        $status = $userRepository->login($username);

        if ( ! $status['ok'] ) {
            $response->code = 500;
            $response->body = json_encode( ["error" => "Internal server error"] );
        } else {
            $user = $status["user"];
            if ( $user == null ) {
                $response->code = 404;
                $response->body = json_encode( ["error" => "User with given username does not exist"] );
            } else {
                if ( ! password_verify($password, $user->password ) ) {
                    $response->code = 404;
                    $response->body = json_encode( ["error" => "Username and password do not match"] );
                } else {
                    $response->code = 200;
                    $response->body = json_encode(["token" => "valid"]);
                }
            }
        }
        return $response;
    }

    public function signup ( Request $request ) : Response {

        $response = new Response();
        $response->setHeader ( "Content-Type", "application/json" );

        if ( ! $request->contentTypeIsJSON() ) {
            $response->code = 400;
            $response->body = json_encode ( ["error" => "Must provide JSON as Content-Type"] );
            return $response;
        }

        $json_body = json_decode($request->body, true);
        if ( $json_body == null ) {
            $response->code = 400;
            $response->body = json_encode ( ["error" => "Bad JSON body"] );
            return $response;
        }

        if ( ! isset ( $json_body['email'] ) ) {
            $response->code = 400;
            $response->body = json_encode ( ["error" => "Must provide email for signup"] );
            return $response;
        }
        $email = $json_body['email'];

        if ( ! isset ( $json_body['name'] ) ) {
            $response->code = 400;
            $response->body = json_encode ( ["error" => "Must provide name for signup"] );
            return $response;
        }
        $name = $json_body['name'];


        if ( ! isset ( $json_body['surname'] ) ) {
            $response->code = 400;
            $response->body = json_encode ( ["error" => "Must provide surname for signup"] );
            return $response;
        }
        $surname = $json_body['surname'];


        if ( ! isset ( $json_body['username'] ) ) {
            $response->code = 400;
            $response->body = json_encode ( ["error" => "Must provide username for signup"] );
            return $response;
        }
        $username = $json_body['username'];

        if ( ! isset ( $json_body['password'] ) ) {
            $response->code = 400;
            $response->body = json_encode ( ["error" => "Must provide password for signup"] );
            return $response;
        }
        $password = $json_body['password'];
        $password = password_hash( $password, PASSWORD_DEFAULT );

        $userRepository = new UserRepository();
        $status = $userRepository->signup($email, $name, $surname, $username, $password);

        if ( ! $status['ok'] ) {
            if ( $status['internal'] ) {
                $response->code = 500;
                $response->body = json_encode( ["error" => "Internal server error" ] );
            } else {
                $response->code = 400;
                $response->body = json_encode(["error" => $status["error"]]);
            }
        } else {
            $response->code = 200;
            $response->body = json_encode( [ "error" => false ] );
        }
        return $response;
    }
}