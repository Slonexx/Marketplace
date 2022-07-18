<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function getProductsExcel(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/product";
        $apiKey = $request->token;
        $client = new ApiClientMC($uri,$apiKey);
        $data = $client->requestGet();

        //dd($data);
        $arrProduct = array();
        foreach ($data->rows as $row) {
            $product = null;
            //dd($row);
            if(property_exists($row, 'article') == true) {
                $product['SKU'] = $row->article;
            } else {
                continue;
            }

            if(property_exists($row, 'name') == true) {
                $product['model'] = $row->name;
            } else {
                continue;
            }

            // if(property_exists($row, 'attributes') == true) {
            //     $product['brand'] = $row->attributes[0]->value;
            // } else {
            //     continue;
            // }

            $isHaveBrand = false;
            $isHaveCheckToAdd = false;
            $checkedMetaToAdd = null;
            foreach($row->attributes as $attribute){
                //print_r($attribute);
                if($attribute->name == "brand (KASPI)"){
                    $product['brand'] = $attribute->value;
                    $isHaveBrand = true;
                } elseif($attribute->name == "Добавлять товар на Kaspi"){
                    if($attribute->value == 1){
                        $isHaveCheckToAdd = true;
                        $checkedMetaToAdd = $attribute->meta;
                    }
                } else {
                    break;
                }
            }

            if($isHaveBrand == false || $isHaveCheckToAdd == false){
                continue;
            }

            if(property_exists($row,'salePrices') == true) {
                if ($row->salePrices[0]->value <= 0) continue;
                else{
                   $product['price'] = $row->salePrices[0]->value;
                   $product['price'] /= 100.0;
                }
            } else {
                continue;
            }
                $product['PP1'] = "yes";
                $product['PP2'] = "yes";
                $product['PP3'] = "yes";
                $product['PP4'] = "yes";
                $product['PP5'] = "no";
                $product['preorder'] = "";
                //dd($product);

                if($checkedMetaToAdd != null)
                $this->changeCheckedAttribute($apiKey,$checkedMetaToAdd,$row->id);

                array_push($arrProduct,$product);
        }

        //dd($arrProduct);

        if(count($arrProduct) > 0 ) {
            $export = new ProductExport($arrProduct);
            $today = date("Y-m-d H:i:s");
            return Excel::download($export, 'products'.$today.'.xlsx');
        } else {
            return response([
                'message' => '0 products exported!'
            ]);
        }

    }

    public function changeCheckedAttribute($apiKey,$meta,$id)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/product/".$id;
        $client = new ApiClientMC($uri,$apiKey);
        $body = [
            'attributes' => [
                0 => [
                    'meta' => $meta,
                    'name' => 'Добавлять товар на Kaspi',
                    'value' => false,
                ],
            ],
        ];
        $client->requestPut($body);
    }

}
