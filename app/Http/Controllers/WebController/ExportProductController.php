<?php

namespace App\Http\Controllers\WebController;

use App\Clients\MsClient;
use App\Http\Controllers\ApiClientMC;
use App\Http\Controllers\Config\getSettingVendorController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExportProductController extends Controller
{
    public function index($accountId){

        $Setting = new getSettingVendorController($accountId);
        $TokenMoySklad = $Setting->TokenMoySklad;

        $Count = $this->getProductCount($TokenMoySklad);

        return view('web.exportProduct', ['accountId' => $accountId, 'Count'=> $Count, "TokenMoySklad"=>$TokenMoySklad]);
    }

    public function getProductCount($apiKey)
    {
        $uri = "https://api.moysklad.ru/api/remap/1.2/entity/product";
        $client = new MsClient($apiKey);

        $metadata = $client->get('https://api.moysklad.ru/api/remap/1.2/entity/product/metadata/attributes')->rows;
        foreach ($metadata as $item){
            if ($item->name == 'Добавлять товар на Kaspi') {
                $uri = "https://api.moysklad.ru/api/remap/1.2/entity/product?filter=".$item->meta->href.'=true';
            }
        }
        $jsonProducts = $client->get($uri);
        $count = 0;
        foreach($jsonProducts->rows as $row){
            $flagAddToKaspi = false;
            $flagCheckNotPublish = false;
            $flagBrand = false;
            if(property_exists($row, 'attributes')){
                 foreach($row->attributes as $attrib){
                    if($attrib->name == 'Добавлять товар на Kaspi' && $attrib->value) {
                        $flagAddToKaspi = true;
                    } elseif ($attrib->name == 'Опубликован на Kaspi' && $attrib->value == 0) {
                        $flagCheckNotPublish = true;
                    } elseif ($row->name == "brand (KASPI)" and $row->value != '') {
                        $flagBrand = true;
                    }
                }
            }
            if($flagAddToKaspi and !$flagCheckNotPublish and $flagBrand){ $count++;
            }
        }
        return $count;
    }

}
