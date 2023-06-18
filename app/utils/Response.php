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


    public function encodeError ( int $errorCode, string $errorMessage ) :void {
        $this->code = $errorCode;
        $this->body = json_encode(
            [
                "ok" => false,
                "error" => $errorMessage
            ]
        );
    }


    public function encodeSuccess ( int $successCode, array $fields = [] ) :void {
        $this->code = $successCode;
        if ( ! empty ( $fields ) ) {
            $this->body = json_encode(
                [
                    "ok" => true,
                    "results" => $fields
                ]
            );
        } else {
            $this->body = json_encode(
                [
                    "ok" => true
                ]
            );
        }
    }
}