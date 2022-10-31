<?php

namespace App\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;

class KassClient
{

    private $apiKey;
    private $password;
    private $kassaNumber;

    private Client $client;

    /**
     * @param $apiKey
     */
    public function __construct($kassaNumber,$password,$apiKey)
    {
        $this->apiKey = $apiKey;
        $this->kassaNumber = $kassaNumber;
        $this->password = $password;

        $this->client = new Client([
            'base_uri' => 'https://api-test.rekassa.kz/api/',
        ]);
    }


    /**
     * @throws GuzzleException
     */
    public function getNewJwtToken() {
        //$uuid_v4 = Str::uuid();
        $res =  $this->client->post('auth/login',[
            'headers' => [
                'Accept' => 'application/json',
            ],
            'query' => [
                'apiKey' => $this->apiKey,
                'format' => 'json',
            ],
            'json' => [
                'number' => $this->kassaNumber,
                'password' => $this->password,
            ],
        ]);
        return json_decode($res->getBody());
    }

    /**
     * @throws GuzzleException
     */
    public function get($uri){
        $jsonWithToken = $this->getNewJwtToken();
        //$uuid_v4 = Str::uuid();
        $res = $this->client->get($uri,[
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$jsonWithToken->token,
            ],
        ]);
        return json_decode($res->getBody());
    }

    /**
     * @throws GuzzleException
     */
    public function post($uri, $body){
        $jsonWithToken = $this->getNewJwtToken();
        //$uuid_v4 = Str::uuid();
        $res = $this->client->post($uri,[
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$jsonWithToken->token,
            ],
            'json' => $body
        ]);
        return json_decode($res->getBody());
    }

    public function getStatusCode(): int
    {
        //$uuid_v4 = Str::uuid();
        $res =  $this->client->post('auth/login',[
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
            ],
            'query' => [
                'apiKey' => $this->apiKey,
                'format' => 'json',
            ],
            'json' => [
                'number' => $this->kassaNumber,
                'password' => $this->password,
            ],
        ]);
        return $res->getStatusCode();
    }


    /**
     * @throws GuzzleException
     */
    public function postWithHeaders($uri, $headers){
        $res = $this->client->post($uri,[
            'headers' => $headers,
        ]);
        return json_decode($res->getBody());
    }

    public function delete($uri){
        $jsonWithToken = $this->getNewJwtToken();
        //$uuid_v4 = Str::uuid();
        $res = $this->client->delete($uri,[
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$jsonWithToken->token,
            ],
        ]);
        return json_decode($res->getBody());
    }

}
