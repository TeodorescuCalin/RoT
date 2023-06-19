<?php

require __DIR__.'/../../vendor/autoload.php';
require_once "Controller.php";

use FireBase\JWT\JWT;
use FireBase\JWT\Key;
use Firebase\JWT\ExpiredException;
class AuthController extends Controller {

    private static string $KEY = "trhgdtyt7pujftr4guuhfrtyu75g";
    private static string $DOMAIN_NAME = "https://127.0.0.1";

    public function __construct ( Request $request) {
        parent :: __construct ( $request );
    }


    public function createJWT ( UserModel $userModel ) :string {

        $date = new DateTimeImmutable();
        $iat = $date->getTimestamp();
        $nbf = $iat;
        $exp = $date->modify('+1 hour')->getTimestamp();
        $data = [
            'iat' => $iat,
            'nbf' => $nbf,
            'iss' => $this::$DOMAIN_NAME,
            'exp' => $exp,
            'id' => $userModel->id,
            'username' => $userModel->username
        ];

        return JWT::encode(
            $data,
            $this::$KEY,
            'HS512'
        );
    }


    public function checkJWT () : array {

        if ( ! isset ( $this->request->cookies['jwt'] ) ) {
            return [
                "ok" => false,
                "error" => "Missing authorization token"
            ];
        }

        $token = $this->request->cookies['jwt'];
        try {
            $decodedToken = JWT::decode($token, new Key($this::$KEY, 'HS512'));
        } catch ( ExpiredException ) {
            return [
                "ok" => false,
                "error" => "Token has expired"
            ];
        } catch ( Exception ) {
            return [
                "ok" => false,
                "error" => "Malformed authorization token"
            ];
        }

        $responseToken = (array) $decodedToken;
        $responseToken['ok'] = true;
        return $responseToken;
    }

}