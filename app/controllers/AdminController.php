<?php

require_once "AuthController.php";
require_once __DIR__."/../repositories/UserRepository.php";

class AdminController extends Controller {

    public function __construct ( Request $request ) {
        parent :: __construct ( $request );
    }


    public function getPage () : Response {

        $response = new Response();
        $response->setHeader("Content-Type", "text/html");

        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }

        $userRepository = new UserRepository();
        if ( ! $userRepository->checkAdmin($decodedToken['id'] ) ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }


        $response->code = 200;
        $response->body = file_get_contents(__DIR__."/../../protected/html/admin/adminMainPage.html");
        return $response;
    }

    public function getLearnPage () : Response {

        $response = new Response();
        $response->setHeader("Content-Type", "text/html");

        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }

        $userRepository = new UserRepository();
        if ( ! $userRepository->checkAdmin($decodedToken['id'] ) ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }


        $response->code = 200;
        $response->body = file_get_contents(__DIR__."/../../protected/html/admin/learnAdminMainPage.html");
        return $response;
    }

    public function getQuizPage () : Response {

        $response = new Response();
        $response->setHeader("Content-Type", "text/html");

        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }

        $userRepository = new UserRepository();
        if ( ! $userRepository->checkAdmin($decodedToken['id'] ) ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }


        $response->code = 200;
        $response->body = file_get_contents(__DIR__."/../../protected/html/admin/quizAdminMainPage.html");
        return $response;
    }

    public function getUserPage () : Response {

        $response = new Response();
        $response->setHeader("Content-Type", "text/html");

        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }

        $userRepository = new UserRepository();
        if ( ! $userRepository->checkAdmin($decodedToken['id'] ) ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }


        $response->code = 200;
        $response->body = file_get_contents(__DIR__."/../../protected/html/admin/userAdminMainPage.html");
        return $response;
    }

    public function getAddNewQuestionPage () : Response {

        $response = new Response();
        $response->setHeader("Content-Type", "text/html");

        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }

        $userRepository = new UserRepository();
        if ( ! $userRepository->checkAdmin($decodedToken['id'] ) ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }


        $response->code = 200;
        $response->body = file_get_contents(__DIR__."/../../protected/html/admin/addNewQuestion.html");
        return $response;
    }

    public function getModifyQuestionPage () : Response {

        $response = new Response();
        $response->setHeader("Content-Type", "text/html");

        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }

        $userRepository = new UserRepository();
        if ( ! $userRepository->checkAdmin($decodedToken['id'] ) ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }


        $response->code = 200;
        $response->body = file_get_contents(__DIR__."/../../protected/html/admin/selectQuestion.html");
        return $response;
    }

    public function getAddNewQuizPage () : Response {

        $response = new Response();
        $response->setHeader("Content-Type", "text/html");

        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }

        $userRepository = new UserRepository();
        if ( ! $userRepository->checkAdmin($decodedToken['id'] ) ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }


        $response->code = 200;
        $response->body = file_get_contents(__DIR__."/../../protected/html/admin/addNewQuiz.html");
        return $response;
    }

    public function getModifyQuizPage () : Response {

        $response = new Response();
        $response->setHeader("Content-Type", "text/html");

        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }

        $userRepository = new UserRepository();
        if ( ! $userRepository->checkAdmin($decodedToken['id'] ) ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }


        $response->code = 200;
        $response->body = file_get_contents(__DIR__."/../../protected/html/admin/modifyQuiz.html");
        return $response;
    }

    public function getSelectQuizPage () : Response {

        $response = new Response();
        $response->setHeader("Content-Type", "text/html");

        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }

        $userRepository = new UserRepository();
        if ( ! $userRepository->checkAdmin($decodedToken['id'] ) ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }


        $response->code = 200;
        $response->body = file_get_contents(__DIR__."/../../protected/html/admin/selectQuiz.html");
        return $response;
    }

}