<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ProductPropertyController extends Controller
{

    public function getAllCategories(Request $request)
    {
        $uri = "https://kaspi.kz/shop/api/products/classification/categories";
        $apiKey = "Oiau+82MUNfcUYPQG9rEyzec3H34OwI5SQ+w6ToodIM=";
        $headers = [
            'Accept' => 'application/json',
            'X-Auth-Token' => $apiKey,
        ];
        $client = new Client();

        $res = $client->request('GET', $uri ,[
            'headers' => $headers,
        ]);

        return json_decode($res->getBody());
    }

    public function getPropertiesByCategory(Request $request)
    {
        $request->validate([
            'category' => 'required|string'
        ]);

        $uri = "https://kaspi.kz/shop/api/products/classification/attributes?c=".$request->category;
        $apiKey = "Oiau+82MUNfcUYPQG9rEyzec3H34OwI5SQ+w6ToodIM=";
        $headers = [
            'Accept' => 'application/json',
            'X-Auth-Token' => $apiKey,
        ];
        $client = new Client();

        $res = $client->request('GET', $uri ,[
            'headers' => $headers,
        ]);

        return json_decode($res->getBody());
    }

    public function getValuesByPropertyCategory(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'property' => 'required|string',
        ]);

        $uri = "https://kaspi.kz/shop/api/products/classification/attribute/values?c="
        .$request->category."&a=".$request->property;

        $apiKey = "Oiau+82MUNfcUYPQG9rEyzec3H34OwI5SQ+w6ToodIM=";
        $headers = [
            'Accept' => 'application/json',
            'X-Auth-Token' => $apiKey,
        ];
        $client = new Client();

        $res = $client->request('GET', $uri ,[
            'headers' => $headers,
        ]);

        return json_decode($res->getBody());
    }

}
