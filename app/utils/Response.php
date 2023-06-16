<?php


class Response {

    public int $code;
    public array $headers = [];
    public string $body;

    public function __construct () {

    }


    public function setHeader ( string $header, string $content ) :void {
        $this->headers[] = $header.": ".$content;
    }
}