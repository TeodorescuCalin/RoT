<?php


require_once "AuthController.php";
require_once ( __DIR__."/../repositories/QuizRepository.php" );
class QuizController extends Controller {

    public function __construct ( Request $request ) {
        parent :: __construct ( $request );
    }

    public function getSelectionPage () : Response {

        $response = new Response();
        $response->setHeader("Content-Type", "text/html");

        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }

        $response->body = file_get_contents(__DIR__."/../../protected/html/quizSelection.html");
        $response->code = 200;
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

        $response->body = file_get_contents(__DIR__."/../../protected/html/quiz.html");
        $response->code = 200;
        return $response;
    }


    public function getQuizzesForUser () : Response {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");

        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }

        $userId = $decodedToken['id'];
        $quizRepository = new QuizRepository();
        $quizzes = $quizRepository->getAllQuizzesForUser($userId);

        $response->encodeSuccess(200, $quizzes);
        return $response;
    }


    public function getQuiz () : Response {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");

        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }

        $quizId = $this->request->pathVariables['quizId'];
        $quizRepository = new QuizRepository();
        $quizModel = $quizRepository->getById($quizId);

        if ( $quizModel == null ) {
            $response->encodeError(404, "No such quiz");
            return $response;
        }

        shuffle($quizModel->questions);

        $resultArray = [];
        $quizArray = (array)$quizModel;
        foreach ( $quizArray['questions'] as $result ) {
            $result = (array)$result;
            unset($result['first_answer_correct']);
            unset($result['second_answer_correct']);
            unset($result['third_answer_correct']);
            $resultArray[] = $result;
        }

        $response->encodeSuccess(200, $resultArray);
        return $response;
    }


    public function checkQuizAnswer () : Response {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");


        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->code = 401;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/401.html");
            return $response;
        }

        if ( ! $this->request->contentTypeIsJSON() ) {
            $response->encodeError(400, "Content-Type must be JSON");
            return $response;
        }

        $json_body = json_decode($this->request->body, true);
        if ( $json_body == null ) {
            $response->encodeError(400, "Bad JSON body");
            return $response;
        }

        if ( ! isset ( $json_body['first_answer_id'] ) || ! isset ( $json_body['first_answer_selected'] )
            || ! isset ( $json_body['second_answer_id'] ) || ! isset ( $json_body['second_answer_selected'] )
                    || ! isset ( $json_body['third_answer_id'] ) || ! isset ( $json_body['third_answer_selected'] ) ) {
            $response->encodeError(400, "Must send answer ids and selections");
            return $response;
        }

        $first_answer_id = $json_body['first_answer_id'];
        $first_answer_selected = $json_body['first_answer_selected'];
        $second_answer_id = $json_body['second_answer_id'];
        $second_answer_selected = $json_body['second_answer_selected'];
        $third_answer_id = $json_body['third_answer_id'];
        $third_answer_selected = $json_body['third_answer_selected'];

        $quizId = $this->request->pathVariables['quizId'];
        $questionId = $this->request->pathVariables['questionId'];

        $questionRepository = new QuizRepository();
        $questionModel = $questionRepository->getQuizQuestion($quizId, $questionId);
        if ( $questionModel == null ) {
            $response->encodeError(404, "Question does not exist");
            return $response;
        }

        if ( $questionModel->first_answer_id != $first_answer_id || $questionModel->first_answer_correct != $first_answer_selected ||
            $questionModel->second_answer_id != $second_answer_id || $questionModel->second_answer_correct != $second_answer_selected  ||
            $questionModel->third_answer_id != $third_answer_id || $questionModel->third_answer_correct != $third_answer_selected
        ) {
            $response->encodeSuccess(200, [
                "solved" => false
            ]);
        } else {
            $response->encodeSuccess(200, [
                "solved" => true
            ]);
        }

        return $response;
    }
}