<?php


class Request {

    public array $headers;
    public array $query;
    public string $body;

    public function __construct () {
        $this->headers = getallheaders();
        $this->query = $_GET;
        $this->body = file_get_contents('php://input');
    }

    public function __toString(): string
    {
        return "Header:".implode("<br>", $this->headers)
            ."<br>Query:".implode("<br>", $this->query)
            ."<br>Body:".$this->body;
    }

    public function contentTypeIsJSON () : bool {
        return isset ( $this->headers["Content-Type"] ) && $this->headers["Content-Type"] == "application/json";
    }

}