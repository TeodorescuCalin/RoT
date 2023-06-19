<?php


class Controller {
    protected Request $request;

    public function __construct( Request $request) {
        $this->request = $request;
    }
}