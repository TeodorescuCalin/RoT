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
    
    public function getSpecificStreetSignsPage () : Response {
        $response = new Response();
        $response->setHeader("Content-Type", "text/html");
        if ( empty ( $this->request->pathVariables['regionType'] ) ) {
            $response->code = 404;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/404.html");
            return $response;
        }

        $regionType = $this->request->pathVariables['regionType'];
        if ( $regionType != 'romanian' && $regionType != 'foreign' ) {
            $response->code = 404;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/404.html");
            return $response;
        }

        $signPage = $this->request->pathVariables['signPage'];
        $response->code = 200;
        $response->body = file_get_contents(__DIR__."/../../protected/html/signPages/".$regionType."/".$signPage.".html");
        if ( ! $response->body ) {
            $response->code = 404;
            $response->body = file_get_contents(__DIR__."/../../protected/html/error/404.html");
            return $response;
        }

        return $response;
    }
}