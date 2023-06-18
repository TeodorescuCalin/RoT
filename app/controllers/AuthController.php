<?php

require __DIR__.'/../../vendor/autoload.php';

use FireBase\JWT\JWT;
use FireBase\JWT\Key;
class AuthController {

    private static string $KEY = "trhgdtyt7pujftr4guuhfrtyu75g";
    private static string $DOMAIN_NAME = "https://127.0.0.1";

    public function __construct () {

    }


    public function createJWT ( UserModel $userModel ) :string {

        $date = new DateTimeImmutable();
        $iat = $date->getTimestamp();
        $exp = $date->modify('+1 hour')->getTimestamp();
        $data = [
            'iat' => $iat,
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

}