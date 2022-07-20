<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\KaspiApiClient;

class ProductPropertyController extends Controller
{

    public function getAllCategories(Request $request)
    {
        $request->validate([
            'tokenKaspi' => 'required|string',
        ]);

        $uri = "https://kaspi.kz/shop/api/products/classification/categories";
        $client = new KaspiApiClient($uri,$request->tokenKaspi);
        return $client->requestGet(false);
    }

    public function getPropertiesByCategory(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'tokenKaspi' => 'required|string',
        ]);

        $uri = "https://kaspi.kz/shop/api/products/classification/attributes?c=".$request->category;
        $client = new KaspiApiClient($uri,$request->tokenKaspi);
        return $client->requestGet(false);
    }

    public function getValuesByPropertyCategory(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'property' => 'required|string',
            'tokenKaspi' => 'required|string',
        ]);

        $uri = "https://kaspi.kz/shop/api/products/classification/attribute/values?c="
        .$request->category."&a=".$request->property;

        $client = new KaspiApiClient($uri,$request->tokenKaspi);
        return $client->requestGet(false);
    }

}
