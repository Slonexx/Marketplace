<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\OrderController;

class ProductController extends Controller
{
    public function getKaspiProducts($apiKey)
    {
        $ordersFromKaspi =app(OrderController::class)->getOrdersFromKaspi($apiKey);
        $productsFromKaspi = [];
        $count = 0;
        $productIds = [];
        foreach($ordersFromKaspi as $order){
            foreach($order['entries'] as $entry){
                $product = null;
                //dd($entry);
                if(array_search($entry['product']->code, $productIds) == false){
                    $productIds[$count] = $entry['product']->code;
                    
                    $product['name'] = $entry['product']->name;
                    $product['article'] = $productIds[$count];
                    $product['attributes'] = [
                        0 => [
                            "meta" => json_decode(file_get_contents(public_path().'/json/brand.json'),true),
                            "name" => "brand (KASPI)",
                            "value" => $entry['product']->manufacturer,
                        ],
                        1 => [
                            "meta" =>json_decode(file_get_contents(public_path().'/json/export_bool.json'),true),
                            "name" => "Добавлять товар на Kaspi",
                            "value" => true,
                        ],
                    ];

                    $product['salePrices'] = [
                        0 => [
                            "value" => $entry['basePrice'],
                            "currency" =>json_decode(file_get_contents(public_path().'/json/currency.json'),true),
                            "priceType" =>json_decode(file_get_contents(public_path().'/json/price_type.json'),true),
                        ],
                    ];
                    $product['uom'] =json_decode(file_get_contents(public_path().'/json/uom.json'),true);
                    $count++;
                    array_push($productsFromKaspi, $product);
                }
            }
        }
        return $productsFromKaspi;
    }

    public function getMsProducts($apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/product";
        $client = new ApiClientMC($uri,$apiKey);
        $jsonProducts = $client->requestGet();
        $productsFromMs = [];
        $count = 0;
        foreach($jsonProducts->rows as $row){
            $productsFromMs[$count] = $row->article;
            $count++;
        }
        //dd($productsFromMs);
    }

    public function getNotAddedProducts($tokenMs,$tokenKaspi) {
       return $productsFromKaspi = $this->getKaspiProducts($tokenKaspi);
        //$productsFromMs = $this->getMsProducts($tokenMs);
    }

    public function insertProducts(Request $request) {
        $request->validate([
            'tokenKaspi' => 'required|string',
            'tokenMs' => 'required|string',
        ]);
       return $this->getNotAddedProducts($request->tokenMs,$request->tokenKaspi);
    }

}
