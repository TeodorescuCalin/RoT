<?php


require_once "Controller.php";

class ErrorController extends Controller {

    public function __construct(Request $request){
        parent::__construct($request);
    }

    public function notFound() : Response {

        $response = new Response();
        $response->setHeader("Content-Type", "text/html");
        $response->code = 404;
        $response->body = file_get_contents(__DIR__."/../../protected/html/error/404.html");
        return $response;
    }
}