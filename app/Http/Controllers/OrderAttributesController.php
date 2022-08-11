<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderAttributesController extends Controller
{
    private ApiClientMC $client;

    public function getAttributeDelivery($stateOrder,$apiKey)
    {

        $state = null;

        switch ($stateOrder) {
            case 'PICKUP':
                $state = "Самовывоз";
                break;
            case 'DELIVERY':
                case 'KASPI_DELIVERY':
                $state = "Доставка";
                break;
            default:
                $state = "";
                break;
        }

        if($state == ""){
            return null;
        }

        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes";
        $this->client = new ApiClientMC($uri,$apiKey);
        $json = $this->client->requestGet();
        $foundedMeta = null;
        $searchVal = null;
        foreach($json->rows as $row)
        {
            if($row->name == "Способ доставки (Kaspi)")
            {
                $foundedMeta = $row->meta;
                $searchVal = $this->searchDeliveryVal($row->customEntityMeta->href,$state);
                break;
            }
        }
        return [
            "meta" => $foundedMeta,
            "value" => $searchVal,
        ];
    }

    public function searchDeliveryVal($uri, $state)
    {
        $this->client->setRequestUrl($uri);
        $entityMetaUri = $this->client->requestGet()->entityMeta->href;
        $this->client->setRequestUrl($entityMetaUri);
        $json = $this->client->requestGet();
        $foundedMetaVal = null;
        foreach($json->rows as $row)
        {
            if($row->name == $state)
            {
                $foundedMetaVal = $row->meta;
                break;
            }
        }
        return [
            "meta" => $foundedMetaVal,
            "name" => $state,
        ];
    }

}
