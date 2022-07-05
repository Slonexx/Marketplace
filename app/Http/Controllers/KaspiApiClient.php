<?php
namespace App\Http\Controllers;
use GuzzleHttp\Client;

class KaspiApiClient {
    
    private $apiKey;
    private $uri;

    public function __construct($uri,$apiKey)
    {
        $this->apiKey = $apiKey;
        $this->uri = $uri;
    }

    public function requestGet($vnd)
    {
        $accept = "";
        if($vnd == true){
            $accept = "application/vnd.api+json";
        } else {
            $accept = "application/json";
        }
        $headers = [
            'Accept' => $accept,
            'X-Auth-Token' => $this->apiKey,
        ];
        $client = new Client();

        $res = $client->request('GET', $this->uri ,[
            'headers' => $headers,
        ]);

        return json_decode($res->getBody());
    }

}