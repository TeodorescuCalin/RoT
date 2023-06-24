<?php

require_once ( __DIR__."/../utils/Request.php" );
require_once ( __DIR__."/../utils/Response.php" );

error_reporting(E_ALL);
ini_set('display_errors', 1);

class App {

    private array $routes = [];
    private string $controller = "ErrorController";
    private string $callback = "notFound";

    public function __construct() {

        $this->addRoute ( "GET", "error", "ErrorController@error");

        $this->addRoute ( "GET", "", "HomeController@index" );
        $this->addRoute ( "GET", "legislation", "HomeController@legislation" );
        $this->addRoute ( "GET", "tips", "HomeController@getTips" );
        $this->addRoute ( "GET", "login", "HomeController@login" );
        $this->addRoute ( "GET", "register", "HomeController@register" );
        $this->addRoute ( "GET", "forgotPassword", "HomeController@forgotPassword" );
        $this->addRoute ( "GET", "about", "HomeController@about" );
        $this->addRoute ( "GET", "streetSigns", "HomeController@streetSigns" );
        $this->addRoute ( "GET", "streetSigns/{regionType}/{signPage}", "HomeController@getSpecificStreetSignsPage" );
        $this->addRoute ( "GET", "help", "HomeController@help" );

        $this->addRoute ( "POST", "login", "UserController@login" );
        $this->addRoute ( "POST", "register", "UserController@register" );
        $this->addRoute ( "GET", "profile/{username}", "UserController@getProfile" );
        $this->addRoute ( "GET", "user_info", "UserController@getUserInfo" );
        $this->addRoute ( "GET", "ranking", "UserController@getRanking" );
        $this->addRoute ( "GET", "ranking_rss", "UserController@getRSSRanking" );
        $this->addRoute("GET", "users", "UserController@getAllUsers");
        $this->addRoute("DELETE", "users/{userId}", "UserController@deleteUser");

        $this->addRoute ( "GET", "learn", "LearnController@getPage" );
        $this->addRoute ( "GET", "learn/question", "LearnController@getQuestionForUser" );
        $this->addRoute ( "GET", "learn/questions/{questionId", "LearnController@getQuestion" );
        $this->addRoute ( "PUT", "learn/questions/{questionId}/status", "LearnController@updateQuestionStatus" );
        $this->addRoute ( "POST", "learn/questions", "LearnController@postQuestion" );
        $this->addRoute ( "PUT", "learn/questions", "LearnController@updateQuestion" );

        $this->addRoute ( "GET", "quizSelection", "QuizController@getSelectionPage" );
        $this->addRoute ( "GET", "quiz/{quizId}", "QuizController@getQuizPage" );
        $this->addRoute ( "GET", "user_quizzes", "QuizController@getQuizzesForUser" );
        $this->addRoute ( "GET", "quizzes/{quizId}", "QuizController@getQuiz" );
        $this->addRoute ( "POST", "quizzes/{quizId}/{questionId}/check", "QuizController@checkQuizAnswer" );
        $this->addRoute ( "PUT", "quizzes/{quizId}", "QuizController@updateUserQuizStatus" );
        $this->addRoute ( "POST", "quizzes", "QuizController@postQuiz" );

        $this->addRoute ( "GET", "admin", "AdminController@getPage" );
        $this->addRoute ( "GET", "learnAdminMainPage", "AdminController@getLearnPage" );
        $this->addRoute ( "GET", "quizAdminMainPage", "AdminController@getQuizPage" );
        $this->addRoute ( "GET", "userAdminMainPage", "AdminController@getUserPage" );
        $this->addRoute ( "GET", "addNewQuestion", "AdminController@getAddNewQuestionPage" );
        $this->addRoute ( "GET", "selectQuestion", "AdminController@getModifyQuestionPage" );
        $this->addRoute ( "GET", "addNewQuiz", "AdminController@getAddNewQuizPage" );
        $this->addRoute ( "GET", "modifyQuiz", "AdminController@getModifyQuizPage" );
    }

    public function parseURL() : void {
        $request = new Request();

        if ( isset( $_GET[ 'url' ] ) ) {
            $url = filter_var($_GET['url'], FILTER_SANITIZE_URL);
            foreach ( $this->routes as $index => $route ) {
                if ( $route['method'] == $_SERVER['REQUEST_METHOD'] ) {
                    $matchResult = $this->matchesRoute ( $url, $route['route'] );

                    if ( $matchResult['match'] ) {

                        unset($_REQUEST['url']);

                        $request->pathVariables = $matchResult['pathVariables'];

                        $callback = explode('@', $route['callback']);
                        $this->controller = $callback[0];
                        $this->callback = $callback[1];
                        break;
                    }
                }
            }
        } else {
            $this->controller = "HomeController";
            $this->callback = "index";
        }

        require_once ( __DIR__."/../controllers/".$this->controller.".php" );

        $controller = new $this->controller($request);

        $response = call_user_func ( [$controller, $this->callback] );
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

    private function matchesRoute ( string $url, string $route ) : array {

        $urlComponents = explode ( "/", $url );
        $routeComponents = explode ( "/", $route );

        if ( count($urlComponents) != count($routeComponents) ) {
            return [
                "match" => false
            ];
        }
        $pathVariables = [];
        for ( $index = 0; $index < count ( $urlComponents ); ++ $index ) {
            if ( preg_match("/^\{([^{}]+)\}$/", $routeComponents[$index] ) ) {
                $pathVarName = trim($routeComponents[$index], "{}");
                $pathVariables[$pathVarName] = $urlComponents[$index];
            } else {
                if ( $routeComponents[$index] != $urlComponents[$index] ) {
                    return [
                        "match" => false
                    ];
                }
            }
        }

        return [
            "match" => true,
            "pathVariables" => $pathVariables
        ];
    }
}