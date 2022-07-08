<?php
namespace App\Http\Controllers;
use GuzzleHttp\Client;

class KaspiApiClient {

    private $client;

    private $apiKey;
    private $url;

    public function __construct($url,$apiKey)
    {
        $this->apiKey = $apiKey;
        $this->url = $url;
    }

    public function setRequestUrl($url){
        $this->url = $url;
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

        $res = $client->request('GET', $this->url ,[
            'headers' => $headers,
        ]);

        return json_decode($res->getBody());
    }


    public function getStatus($vnd)
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

        return $res->getStatusCode();
    }

}
