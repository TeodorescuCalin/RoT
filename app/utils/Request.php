<?php


class Request {

    public array $headers;
    public array $query;
    public string $body;
    public array $cookies;
    public array $pathVariables;

    public function __construct () {
        $this->headers = getallheaders();
        $this->query = $_GET;
        $this->body = file_get_contents('php://input');
        $this->cookies = $_COOKIE;
    }

    public function contentTypeIsJSON () : bool {
        return isset ( $this->headers["Content-Type"] ) && $this->headers["Content-Type"] == "application/json";
    }

}