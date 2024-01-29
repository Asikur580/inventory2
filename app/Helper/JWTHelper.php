<?php

namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHelper
{

    public static function CreateToken($userEmail,$userID,$role){
        $key=env('JWT_KEY');
        $payload=[
            'iss'=>'laravel-demo',
            'iat'=>time(),
            'exp'=>time()+60*60,
            'userEmail'=>$userEmail,
            'userID'=>$userID,
            'role'=>$role
        ];
        return JWT::encode($payload,$key,'HS256');
    }

    public static function CreateTokenForSetPassword($userEmail){
        $key=env('JWT_KEY');
        $payload=[
            'iss'=>'laravel-demo',
            'iat'=>time(),
            'exp'=>time()+60*5,
            'userEmail'=>$userEmail,
            'userID'=>''
        ];
        return JWT::encode($payload,$key,'HS256');
    }

    public static function DecodeToken($token){
        try {
            if($token==null){
                return "unauthorized";
            }
            else{
                $key=env('JWT_KEY');
                return JWT::decode($token,new Key($key,'HS256'));
            }

        }catch (Exception $exception){
            return "unauthorized";
        }

    }

}
