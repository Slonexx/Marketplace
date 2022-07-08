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
                    //dd($productIds[$count]);
                    $product['name'] = $entry['product']->name;
                    $product['article'] = $productIds[$count];
                    $product['attributes'] = [
                        0 => [
                            "meta" => $this->getContentJson('brand'),
                            "name" => "brand (KASPI)",
                            "value" => $entry['product']->manufacturer,
                        ],
                        1 => [
                            "meta" =>$this->getContentJson('export_bool'),
                            "name" => "Добавлять товар на Kaspi",
                            "value" => true,
                        ],
                    ];

                    $product['salePrices'] = [
                        0 => [
                            "value" => $entry['basePrice'] * 100,
                            "currency" => $this->getContentJson('currency'),
                            "priceType" => $this->getContentJson('price_type'),
                        ],
                    ];
                    $product['uom'] = $this->getContentJson('uom');
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
        foreach($jsonProducts->rows as $key=>$row){
            $productsFromMs[$count] = $row->article;
            $count++;
        }
        //dd($productsFromMs);
        return $productsFromMs;
    }

    public function getNotAddedProducts($tokenMs,$tokenKaspi) 
    {
       $productsFromKaspi = $this->getKaspiProducts($tokenKaspi);
       $productsFromMs = $this->getMsProducts($tokenMs);
       $notAddedProducts = [];
       foreach($productsFromKaspi as $product){
            if(in_array($product['article'],$productsFromMs) == false){
                array_push($notAddedProducts, $product);
            }
       }
       return $notAddedProducts;
    }

    public function insertProducts(Request $request) {
        $request->validate([
            'tokenKaspi' => 'required|string',
            'tokenMs' => 'required|string',
        ]);
        
        $products = $this->getNotAddedProducts($request->tokenMs,$request->tokenKaspi);

        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/product";
        $client = new ApiClientMC($uri,$request->tokenMs);

        foreach ($products as $product){
            $client->requestPost($product);
        }
        return response([
            "mesage" => "Inserted products:".count($products),
        ]);
    }

    private function getContentJson($filename) {
        $path = public_path().'/json'.'/'.$filename.'.json';
        return json_decode(file_get_contents($path),true);
    }

}
