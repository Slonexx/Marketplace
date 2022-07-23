<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UomController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\PriceTypeController;
use App\Http\Controllers\ProductAttributesController;

class ProductController extends Controller
{
    public function getKaspiProducts($apiKeyKaspi,$apiKeyMs, $urlKaspi)
    {
        $ordersFromKaspi =app(OrderController::class)->getOrdersFromKaspi($apiKeyKaspi,$urlKaspi);
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
                            "meta" => app(ProductAttributesController::class)->getAttribute("brand (KASPI)",$apiKeyMs),
                            "name" => "brand (KASPI)",
                            "value" => $entry['product']->manufacturer,
                        ],
                        1 => [
                            "meta" => app(ProductAttributesController::class)->getAttribute("Добавлять товар на Kaspi",$apiKeyMs),
                            "name" => "Добавлять товар на Kaspi",
                            "value" => true,
                        ],
                        2 => [
                            "meta" => app(ProductAttributesController::class)->getAttribute("Опубликован на Kaspi",$apiKeyMs),
                            "name" => "Опубликован на Kaspi",
                            "value" => true,
                        ]
                    ];

                    $product['salePrices'] = [
                        0 => [
                            "value" => $entry['basePrice'] * 100,
                            "currency" => app(CurrencyController::class)->getKzCurrency($apiKeyMs),
                            "priceType" => app(PriceTypeController::class)->getPriceType($apiKeyMs),
                        ],
                    ];
                    $product['uom'] = app(UomController::class)->getUom($apiKeyMs);
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
        $productsFromMsOpt1 = [];
        $productsFromMsOpt2 = [];
        
        $count = 0;
        foreach($jsonProducts->rows as $key=>$row){
            $productsFromMsOpt1[$count] = $row->article;
            $productsFromMsOpt2[$count] = $row->name;
            $count++;
        }
        //dd($productsFromMs);
        return [
            'articles' => $productsFromMsOpt1,
            'names' => $productsFromMsOpt2,
        ];
    }

    public function getNotAddedProducts($tokenMs,$tokenKaspi, $urlKaspi, $option) 
    {
       $productsFromKaspi = $this->getKaspiProducts($tokenKaspi,$tokenMs, $urlKaspi);
       $productsFromMs = $this->getMsProducts($tokenMs);
       $notAddedProducts = [];
       foreach($productsFromKaspi as $product){
            switch ($option) {
                case 2:
                    if(in_array($product['article'],$productsFromMs["articles"]) == false){
                        array_push($notAddedProducts, $product);
                    }
                    break;
                case 1:
                    if(in_array($product['name'],$productsFromMs["names"]) == false){
                        array_push($notAddedProducts, $product);
                    }
                    break;
                case 3:
                    if(
                        in_array($product['name'],$productsFromMs["names"]) == false
                        &&
                        in_array($product['article'],$productsFromMs["articles"]) == false
                    ){
                        array_push($notAddedProducts, $product);
                    }
                    break;
            }
            
       }
       return $notAddedProducts;
    }

    public function insertProducts(Request $request) {
        $request->validate([
            'tokenKaspi' => 'required|string',
            'tokenMs' => 'required|string',
            'state' => 'required|string',
            'fdate' => 'required|string',
            'sdate' => 'required|string',
            'option' => 'required|integer',
        ]);

        $fdate = app(TimeFormatController::class)->getMilliseconds($request->fdate);
        $sdate =  app(TimeFormatController::class)->getMilliseconds($request->sdate);

        $urlKaspi = "https://kaspi.kz/shop/api/v2/orders?page[number]=0&page[size]=20&filter[orders][state]=".
        $request->state."&filter[orders][creationDate][\$ge]=".
        $fdate."&filter[orders][creationDate][\$le]=".$sdate;
        
        $products = $this->getNotAddedProducts($request->tokenMs,$request->tokenKaspi,$urlKaspi,$request->option);

        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/product";
        $client = new ApiClientMC($uri,$request->tokenMs);

        foreach ($products as $product){
            $client->requestPost($product);
        }
        return response([
            "mesage" => "Inserted products:".count($products),
        ]);
    }

    // private function getContentJson($filename) {
    //     $path = public_path().'/json'.'/'.$filename.'.json';
    //     return json_decode(file_get_contents($path),true);
    // }

}
