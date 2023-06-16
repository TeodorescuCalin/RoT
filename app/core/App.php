<?php

require_once ( __DIR__."/../utils/Request.php" );
require_once ( __DIR__."/../utils/Response.php" );

error_reporting(E_ALL);
ini_set('display_errors', 1);

class App {

    private array $routes = [];
    private string $controller = "home";
    private string $callback = "index";

    public function __construct() {

        $this->addRoute ( "POST", "/login", "UserController@login" );
        $this->addRoute ( "POST", "/signup", "UserController@signup" );
    }

    public function parseURL() : void {
        if ( isset( $_GET[ 'url' ] ) ) {
            foreach ( $this->routes as $index => $route ) {
                if ( $route['method'] == $_SERVER['REQUEST_METHOD'] && $route['route'] == $_GET['url'] ) {

                    unset($_REQUEST['url']);
                    $callback = explode ( '@', $route['callback'] );
                    $this->controller = $callback[0];
                    $this->callback = $callback[1];

                    break;
                }
            }
        }

        require_once ( __DIR__."/../controllers/".$this->controller.".php" );

        $response = call_user_func ( [new $this->controller, $this->callback], new Request() );
        foreach ( $response->headers as $header ) {
            header ( $header );
        }
        http_response_code ( $response->code );
        echo $response->body;
    }

    private function addRoute ( $method, $route, $callback ) :void {
        $this->routes[] = [
            "method" => $method,
            "route" => $route,
            "callback" => $callback
        ];
    }
}