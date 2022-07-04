<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ProductPropertyController extends Controller
{
    public function getPropertiesByCategory(Request $request)
    {
        $request->validate([
            'category' => 'required|string'
        ]);

        $uri = "/shop/api/products/classification/attributes?c=";
        $apiKey = "Oiau+82MUNfcUYPQG9rEyzec3H34OwI5SQ+w6ToodIM=";
        $headers = [
            'Accept' => 'application/json',
            'X-Auth-Token' => $apiKey,
        ];
        $client = new Client(['base_uri' => 'https://kaspi.kz']);

        $res = $client->request('GET', $uri ,[
            'headers' => $headers,
        ]);

        return json_decode($res->getBody());

    }
}
