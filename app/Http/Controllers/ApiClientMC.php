<?php
namespace App\Http\Controllers;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

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
            'Authorization' => $this->apiKey,
            'Content-Type' => 'application/json',
        ];

        $client = new Client([
            'headers' => $headers,
        ]);


       // try {
            $res = $client->post($this->uri,[
            'body' => json_encode($body),
            ]);
       // } catch (ClientException $e) {
         //   dd($e->getMessage());
       // }

        

        return json_decode($res->getBody());
    }

    public function requestPut($body){
        $headers = [
            'Authorization' => $this->apiKey,
            'Content-Type' => 'application/json',
        ];

        $client = new Client([
            'headers' => $headers,
        ]);
        $res = $client->put($this->uri,[
           'body' => json_encode($body),
        ]);

        return json_decode($res->getBody());
    }

    public function requestDelete()
    {
        $headers = [
            'Authorization' => $this->apiKey,
        ];

        $client = new Client([
            'headers' => $headers,
        ]);

        $res = $client->delete($this->uri);
        //return json_decode($res->getBody());
    }

}
