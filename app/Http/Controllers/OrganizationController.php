<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function getKaspiOrganization($apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/organization?search=Kaspi";
        $client = new ApiClientMC($uri,$apiKey);
        $json = $client->requestGet();
        $foundedMeta = null;
        foreach($json->rows as $row){
            $foundedMeta = [
                "meta" => [
                    "href" => $row->meta->href,
                    "metadataHref" =>$row->meta->metadataHref,
                    "type" => $row->meta->type,
                    "mediaType" => $row->meta->mediaType,
                    "uuidHref" => $row->meta->uuidHref,
                ],
            ];
            break;
        }
        if (is_null($foundedMeta) == true){
            return $this->createOrganization($apiKey);
        } else return $foundedMeta;
    }

    public function createOrganization($apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/organization";
        $client = new ApiClientMC($uri,$apiKey);
        $organization = [ 
            "name" => "Kaspi",
            "description" => "Kaspi shop account",
        ];
        $createdMeta = $client->requestPost($organization)->meta;

        return [
            "meta" => [
                "href" => $createdMeta->href,
                "metadataHref" =>$createdMeta->metadataHref,
                "type" => $createdMeta->type,
                "mediaType" => $createdMeta->mediaType,
                "uuidHref" => $createdMeta->uuidHref,
            ],
        ];
    }
}
