<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class libController extends Controller
{



    public function makeHttpRequest(string $method, string $url, string $bearerToken, $body = null){
        $opts = $body
            ? array('http' =>
                array(
                    'method'  => $method,
                    'header'  => array('Authorization: Bearer ' . $bearerToken, "Content-type: application/json"),
                    'content' => $body
                )
            )
            : array('http' =>
                array(
                    'method'  => $method,
                    'header'  => 'Authorization: Bearer ' . $bearerToken
                )
            );
        $context = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);
        return json_decode($result);
    }

    function buildJWT() {
        $token = array(
            "sub" => cfg()->appUid,
            "iat" => time(),
            "exp" => time() + 300,
            "jti" => bin2hex(random_bytes(32))
        );
        return JWT::encode($token, cfg()->secretKey);
    }

    function vendorApi(): VendorApiController {
        return $GLOBALS['vendorApi'];
    }


}
