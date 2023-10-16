<?php

namespace App\Http\Controllers;

use App\Clients\MsClient;
use Illuminate\Http\Request;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function getProductsExcel(Request $request, $TokenMoySklad)
    {



        $uri = "https://api.moysklad.ru/api/remap/1.2/entity/product";
        $apiKey = $TokenMoySklad;
        $client = new MsClient($apiKey);

        $metadata = $client->get('https://api.moysklad.ru/api/remap/1.2/entity/product/metadata/attributes')->rows;
        foreach ($metadata as $item){
            if ($item->name == 'Добавлять товар на Kaspi') {
                $uri = "https://api.moysklad.ru/api/remap/1.2/entity/product?filter=".$item->meta->href.'=true';
            }
        }

        $data = $client->get($uri);

        $arrProduct = array();
        foreach ($data->rows as $row) {
            $product = null;

            if (property_exists($row, 'article') == true) {
                $product['SKU'] = $row->article;
            } else {
                continue;
            }

            if (property_exists($row, 'name') == true) {
                $product['model'] = $row->name;
            } else {
                continue;
            }

            $isHaveBrand = false;
            $isHaveCheckToAdd = false;
            $checkedMetaToAdd = null;
            $isAddedToKaspi = false;


            foreach ($row->attributes as $attribute) {

                if ($attribute->name == "brand (KASPI)") {
                    $product['brand'] = $attribute->value;
                    $isHaveBrand = true;
                } elseif ($attribute->name == "Добавлять товар на Kaspi") {
                    if ($attribute->value) {
                        $isHaveCheckToAdd = true;
                        $checkedMetaToAdd = $attribute->meta;
                    }
                } elseif ($attribute->name == 'Опубликован на Kaspi') {
                    if ($attribute->value) {
                        $isAddedToKaspi = true;
                    }
                } else {
                    break;
                }
            }

            if ($isAddedToKaspi) { continue; }
            //dd($product, $isHaveBrand, $isHaveCheckToAdd);
            if ($isHaveBrand == false || $isHaveCheckToAdd == false) {
                continue;
            }

            if (property_exists($row, 'salePrices') == true) {
                if ($row->salePrices[0]->value <= 0) continue;
                else {
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

            if ($checkedMetaToAdd != null) $this->changeCheckedAttribute($apiKey, $checkedMetaToAdd, $row->id);

            $arrProduct[] = $product;
        }

        //dd($arrProduct);

        if (count($arrProduct) > 0) {
            $export = new ProductExport($arrProduct);
            $today = date("Y-m-d H:i:s");
            return Excel::download($export, 'products' . $today . '.xlsx');
        } else {
            return redirect()->back();
        }

    }

    public function changeCheckedAttribute($apiKey, $meta, $id)
    {
        $uri = "https://api.moysklad.ru/api/remap/1.2/entity/product/" . $id;
        $client = new ApiClientMC($uri, $apiKey);
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
