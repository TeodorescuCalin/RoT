<?php

require_once (__DIR__."/Controller.php");

class HomeController extends Controller  {

    public function __construct ( Request $request ) {
        parent :: __construct ( $request );
    }


    public function index () : Response {
        $response = new Response();
        $response->setHeader("Content-Type", "text/html");
        $response->code = 200;
        $response->body = file_get_contents(__DIR__."/../../protected/html/index.html");
        return $response;
    }
    
    public function legislation () : Response {
        $response = new Response();
        $response->setHeader("Content-Type", "text/html");
        $response->code = 200;
        $response->body = file_get_contents(__DIR__."/../../protected/html/legislation.html");
        return $response;
    }
    
    public function login () : Response {
        $response = new Response();
        $response->setHeader("Content-Type", "text/html");
        $response->code = 200;
        $response->body = file_get_contents(__DIR__."/../../protected/html/login.html");
        return $response;
    }
    
    public function register () : Response {
        $response = new Response();
        $response->setHeader("Content-Type", "text/html");
        $response->code = 200;
        $response->body = file_get_contents(__DIR__."/../../protected/html/register.html");
        return $response;
    }

    public function forgotPassword () : Response {
        $response = new Response();
        $response->setHeader("Content-Type", "text/html");
        $response->code = 200;
        $response->body = file_get_contents(__DIR__."/../../protected/html/forgotPassword.html");
        return $response;
    }
    
    public function about () : Response {
        $response = new Response();
        $response->setHeader("Content-Type", "text/html");
        $response->code = 200;
        $response->body = file_get_contents(__DIR__."/../../protected/html/about.html");
        return $response;
    }
    
    public function help () : Response {
        $response = new Response();
        $response->setHeader("Content-Type", "text/html");
        $response->code = 200;
        $response->body = file_get_contents(__DIR__."/../../protected/html/help.html");
        return $response;
    }

    public function streetSigns () : Response {
        $response = new Response();
        $response->setHeader("Content-Type", "text/html");
        $response->code = 200;
        $response->body = file_get_contents(__DIR__."/../../protected/html/streetSigns.html");
        return $response;
    }
    
    
}