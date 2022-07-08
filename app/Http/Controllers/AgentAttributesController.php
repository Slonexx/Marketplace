<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgentAttributesController extends Controller
{
    private ApiClientMC $client;

    public function getAttributes($apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/counterparty/metadata/attributes";
        $this->client = new ApiClientMC($uri,$apiKey);
        $json = $this->client->requestGet();
        $foundedMeta = null;
        $searchVal = null;
        foreach($json->rows as $row)
        {
            if($row->name == "Государственное учреждение")
            {
                $foundedMeta = $row->meta;
                $searchVal = $this->searchGosVal($row->customEntityMeta->href);
                break;
            }
        }
        return [
            "meta" => $foundedMeta,
            "value" => $searchVal,
        ];
    }

    public function searchGosVal($uri)
    {
        $this->client->setRequestUrl($uri);
        $entityMetaUri = $this->client->requestGet()->entityMeta->href;
        $this->client->setRequestUrl($entityMetaUri);
        $json = $this->client->requestGet();
        $foundedMetaVal = null;
        foreach($json->rows as $row)
        {
            if($row->name == "Нет")
            {
                $foundedMetaVal = $row->meta;
                break;
            }
        }
        return [
            "meta" => $foundedMetaVal,
            "name" => "Нет",
        ];
    }

}
