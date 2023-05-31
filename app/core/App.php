<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once(__DIR__."/../repositories/UserRepository.php");
class App {

    public function __construct() {
//        try {
//            $userRep = new UserRepository();
//            echo json_encode($userRep->getById(1), JSON_PRETTY_PRINT);
//        } catch ( PDOException $e ) {
//            echo $e->getMessage();
//        }
    }

    public function parseURL() : void {
        if ( isset($_GET['url'] ) ) {
            $parsedURL = explode (
                '/',
                filter_var (
                    rtrim($_GET['url'],
                    '/'
                    ),
            FILTER_SANITIZE_URL)
            );

            $userId = $parsedURL[0];
            $userRep = new UserRepository();
            echo json_encode($userRep->getById($userId), JSON_PRETTY_PRINT);
        }
    }
}