<?php
namespace App\Http\Controllers;
use GuzzleHttp\Client;

class ApiClientMC {

    private $apiKey;
    private $uri;

    public function __construct($uri,$apiKey)
    {
        $this->apiKey = $apiKey;
        $this->uri = $uri;
    }

    public function setRequestUrl($uri){
        $this->uri = $uri;
    }

    public function requestGet()
    {
        //$accept = "application/json";
        // if($vnd == true){
        //     $accept = "application/vnd.api+json";
        // } else {
        //     $accept = "application/json";
        // }
        $headers = [
            //'Accept' => $accept,
            'Authorization' => $this->apiKey,
        ];
        $client = new Client();

        $res = $client->request('GET', $this->uri ,[
            'headers' => $headers,
        ]);

        return json_decode($res->getBody());
    }

    public function requestPost($body){
        $headers = [
            //'Accept' => $accept,
            'Authorization' => $this->apiKey,
            'Content-Type' => 'application/json',
        ];

        $client = new Client([
            'headers' => $headers,
        ]);

        $res = $client->post($this->uri,[
            'body' => json_encode($body),
        ]);

        return json_decode($res->getBody());
    }

}
