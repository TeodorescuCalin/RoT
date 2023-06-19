<?php

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
}