<?php

require_once ( __DIR__."/../repositories/LearnQuestionRepository.php" );
require_once ( __DIR__."/../repositories/UserRepository.php" );
require_once ( __DIR__."/../repositories/AnswerRepository.php" );
require_once ( __DIR__."/../models/AnswerModel.php" );
require_once (__DIR__."/AuthController.php");

class LearnController extends Controller {


    public function __construct(Request $request){
        parent::__construct($request);
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

        $response->body = file_get_contents(__DIR__."/../../protected/html/learn.html");
        $response->code = 200;
        return $response;
    }


    public function getQuestionForUser () : Response {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");

        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->encodeError(401, "You are not authenticated");
            return $response;
        }

        $questionRepository = new LearnQuestionRepository();
        $questionModel = $questionRepository->getForUser($decodedToken['id']);

        if ( $questionModel == null ) {
            $response->encodeError(404, "Question does not exist");
            return $response;
        }

        shuffle($questionModel->answers);
        $response->encodeSuccess(200, (array)$questionModel );
        return $response;
    }


    public function updateQuestionStatus () : Response {

        $response = new Response();
        $response->setHeader("Content-Type", "application/json");

        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->encodeError(401, "You are not authenticated");
            return $response;
        }
        $userId = $decodedToken['id'];

        if ( empty ( $this->request->pathVariables['questionId'] )) {
            $response->encodeError(400, "Must provide the id of the updating question");
            return $response;
        }
        $questionId = $this->request->pathVariables['questionId'];


        if ( ! $this->request->contentTypeIsJSON() ) {
            $response->encodeError(400, "Must provide JSON as Content-Type");
            return $response;
        }

        $json_body = json_decode($this->request->body, true);
        if ( $json_body == null ) {
            $response->encodeError(400, "Bad JSON body");
            return $response;
        }

        if ( isset ( $json_body['skipped'] ) ) {
            $answerArray = [];
            $questionStatus = 'skipped';
        } else {
            if ( empty ( $json_body['answers'] ) ) {
                $response->encodeError(400, "Must provide the list of answers");
                return $response;
            }
            $answerArray = $this->computeQuestionResult($questionId, $json_body['answers']);
            if ( empty ( $answerArray ) ) {
                $response->encodeError(404, "Question not found");
                return $response;
            }
            $questionStatus = $answerArray['status'];

            unset($answerArray['status']);
        }

        $questionRepository = new LearnQuestionRepository();
        $userQuestionModel = $questionRepository->getStatusForUser($questionId, $userId);
        if ( $userQuestionModel == null ) {
            $userQuestionModel = new UserLearnQuestionModel();
            $userQuestionModel->questionId = $questionId;
            $userQuestionModel->userId = $userId;
            $userQuestionModel->status = $questionStatus;
            if ( $questionRepository->createQuestionStatus($userQuestionModel ) )  {
                $response->encodeSuccess(201, $answerArray);
            } else {
                $response->encodeError(500, "Internal server error");
            }
            return $response;
        }

        if ( $userQuestionModel->status == 'skipped' ) {
            $userQuestionModel->status = $questionStatus;
        } else {
            if ( $userQuestionModel->status == 'failed' ) {
                if ( $questionStatus == 'passed' ) {
                    $userQuestionModel->status = $questionStatus;
                }
            }
        }
        if ( $questionRepository->updateQuestionStatus($userQuestionModel ) )  {
            $response->encodeSuccess(201, $answerArray);
        } else {
            $response->encodeError(500, "Internal server error");
        }
        return $response;
    }


    private function computeQuestionResult ($questionId, $answers) : array {
        $questionRepository = new LearnQuestionRepository();
        $questionModel = $questionRepository->getById($questionId);
        if ( $questionModel == null ) {
            return [];
        }
        $responseArray = [];

        $status = 'passed';
        $correctAnswerCount = count( $questionModel->answers );

        if ( $questionModel->type == 'multipleChoice' ) {
            foreach ($answers as $answer) {
                $exists = false;
                foreach ($questionModel->answers as $correctAnswer) {
                    if ($correctAnswer['id'] == $answer['id']) {
                        if (!$answer['selected']) {
                            $status = 'failed';
                        }

                        $responseArray[] = [
                            'id' => $answer['id'],
                            'correct' => $answer['selected']
                        ];
                        $correctAnswerCount--;
                        $exists = true;
                        break;
                    }
                }

                if (!$exists) {
                    if ($answer['selected']) {
                        $status = 'failed';
                    }

                    $responseArray[] = [
                        'id' => $answer['id'],
                        'correct' => !$answer['selected']
                    ];
                }
            }
        } else {
            foreach ($answers as $answer) {
                $exists = false;
                foreach ($questionModel->answers as $correctAnswer) {
                    if ($correctAnswer['id'] == $answer['id']) {
                        if ($answer['count'] != $correctAnswer['count']) {
                            $status = 'failed';
                            $responseArray[] = [
                                'id' => $answer['id'],
                                'correct' => false
                            ];
                        } else {
                            $responseArray[] = [
                                'id' => $answer['id'],
                                'correct' => true
                            ];
                        }
                        $correctAnswerCount--;
                        $exists = true;
                        break;
                    }
                }

                if (!$exists) {
                    if ($answer['count'] != 0) {
                        $status = 'failed';
                    }

                    $responseArray[] = [
                        'id' => $answer['id'],
                        'correct' => $answer['count'] == 0
                    ];
                }
            }

        }

        if ( $correctAnswerCount != 0 ) {
            $status = 'failed';
        }

        $responseArray['status'] = $status;
        return $responseArray;
    }


    public function getQuestionAnswers () : Response {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");

        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->encodeError(401, "You are not authenticated");
            return $response;
        }

        if ( empty ( $this->request->pathVariables['questionId'] )) {
            $response->encodeError(400, "Must provide the id of the updating question");
            return $response;
        }
        $questionId = $this->request->pathVariables['questionId'];
        $questionRepository = new LearnQuestionRepository();
        $questionModel = $questionRepository->getById($questionId);

        if ( $questionModel == null ) {
            $response->encodeError(404, "Question does not exist");
            return $response;
        }

        $response->encodeSuccess(200, ["answers" => $questionModel->answers]);
        return $response;
    }


    public function postQuestion () : Response {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");


        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->encodeError(401, "You are not authenticated");
            return $response;
        }

        $userRepository = new UserRepository();
        if ( ! $userRepository->checkAdmin($decodedToken['id'] ) ) {
            $response->encodeError(401, "You are not an admin");
            return $response;
        }

        if ( ! $this->request->contentTypeIsJSON() ) {
            $response->encodeError(400, "Must provide JSON as Content-Type");
            return $response;
        }

        $json_body = json_decode($this->request->body, true);
        if ( $json_body == null ) {
            $response->encodeError(400, "Bad JSON body");
            return $response;
        }

        $questionModel = new LearnQuestionModel();
        $questionModel->text = $json_body['text'];
        $questionModel->explanation = $json_body['explanation'];
        $questionModel->image_path = $json_body['image_path'];
        $questionModel->type = $json_body['type'];
        $questionModel->category = $json_body['category'];
        $questionModel->answer_count = $json_body['answer_count'];

        $answerList = [];

        $answerRepository = new AnswerRepository();
        foreach ( $json_body['answers'] as $answer ) {
            $answerModel = $answerRepository->getByText($answer['text']);
            if ( $answerModel == null ) {
                $answerModel = new AnswerModel();
                $answerModel->text = $answer['text'];
                $answerRepository->create($answerModel);

            }
            if ( isset ( $answer['count'] ) ) {
                $answerList[] = [
                    "id" => $answerModel->id,
                    "count" => $answer['count']
                ];
            } else {
                $answerList[] = [
                    "id" => $answerModel->id,
                    "count" => null
                ];
            }
        }
        $questionModel->answers = $answerList;

        $questionRepository = new LearnQuestionRepository();
        $questionRepository->create($questionModel);


       $response->encodeSuccess(200);

        return $response;
    }

    public function getQuestion() : Response {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");


        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->encodeError(401, "You are not authenticated");
            return $response;
        }

        $userRepository = new UserRepository();
        if ( ! $userRepository->checkAdmin($decodedToken['id'] ) ) {
            $response->encodeError(401, "You are not an admin");
            return $response;
        }

        $questionRepository = new LearnQuestionRepository();
        $questionId = $this->request->pathVariables['questionId'];
        $questionModel = $questionRepository->getById($questionId);

        if ( $questionModel == null ) {
            $response->encodeError(404, "Question does not exist");
            return $response;
        }

        $response->encodeSuccess(200, (array)$questionModel);
        return $response;

    }


    public function updateQuestion () : Response {
        $response = new Response();
        $response->setHeader("Content-Type", "application/json");


        $authController = new AuthController($this->request);
        $decodedToken = $authController->checkJWT();
        if ( ! $decodedToken['ok'] ) {
            $response->encodeError(401, "You are not authenticated");
            return $response;
        }

        $userRepository = new UserRepository();
        if ( ! $userRepository->checkAdmin($decodedToken['id'] ) ) {
            $response->encodeError(401, "You are not an admin");
            return $response;
        }

        if ( ! $this->request->contentTypeIsJSON() ) {
            $response->encodeError(400, "Must provide JSON as Content-Type");
            return $response;
        }

        $json_body = json_decode($this->request->body, true);
        if ( $json_body == null ) {
            $response->encodeError(400, "Bad JSON body");
            return $response;
        }

        $questionModel = new LearnQuestionModel();
        $questionModel->id = $this->request->pathVariables['questionId'];
        $questionModel->text = $json_body['text'];
        $questionModel->explanation = $json_body['explanation'];
        $questionModel->image_path = $json_body['image_path'];
        $questionModel->type = $json_body['type'];
        $questionModel->category = $json_body['category'];
        $questionModel->answer_count = $json_body['answer_count'];

        $answerList = [];

        $answerRepository = new AnswerRepository();
        foreach ( $json_body['answers'] as $answer ) {
            $answerModel = $answerRepository->getByText($answer['text']);
            if ( $answerModel == null ) {
                $answerModel = new AnswerModel();
                $answerModel->text = $answer['text'];
                $answerRepository->create($answerModel);

            }
            if ( isset ( $answer['count'] ) ) {
                $answerList[] = [
                    "id" => $answerModel->id,
                    "count" => $answer['count']
                ];
            } else {
                $answerList[] = [
                    "id" => $answerModel->id,
                    "count" => null
                ];
            }
        }
        $questionModel->answers = $answerList;

        $questionRepository = new LearnQuestionRepository();
        $questionRepository->update($questionModel);


        $response->encodeSuccess(200);

        return $response;
    }
}