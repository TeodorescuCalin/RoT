<?php


class Home {

    public function __construct () {

    }


    public function index () : Response {
        $response = new Response();
        $response->setHeader("Content-Type", "text/plain");
        $response->code = 200;
        $response->body = "You ain't supposed to be here";
        return $response;
    }
}