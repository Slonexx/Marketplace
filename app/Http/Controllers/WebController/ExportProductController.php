<?php

namespace App\Http\Controllers\WebController;

use App\Http\Controllers\ApiClientMC;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExportProductController extends Controller
{
    public function index($accountId){
        return view('web.exportProduct', ['accountId' => $accountId]);
    }

    public function getProductCount($apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/product";
        $client = new ApiClientMC($uri,$apiKey);
        $jsonProducts = $client->requestGet();
        $count = 0;
        foreach($jsonProducts->rows as $row){
            $flagAddToKaspi = false;
            $flagCheckNotPublish = false;
            if(property_exists($row, 'attributes')){
                 foreach($row->attributes as $attrib){
                    if( $attrib->name == 'Добавлять товар на Kaspi' 
                        && $attrib->type == 'boolean' && $attrib->value == 1)
                    {
                        $flagAddToKaspi = true;
                    } elseif ($attrib->name == 'Опубликован на Kaspi' 
                    && $attrib->type == 'boolean' && $attrib->value == 0) {
                        $flagCheckNotPublish = true;
                    }
                }
            }
            if($flagAddToKaspi == true && $flagCheckNotPublish == true){
                $count++;
            }
        }
        return $count;
    }

}
