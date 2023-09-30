<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function getKaspiOrganization($nameOrganization,$apiKey)
    {
        $uri = "https://api.moysklad.ru/api/remap/1.2/entity/organization?search=".$nameOrganization;
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
            return $this->createOrganization($nameOrganization,$apiKey);
        } else return $foundedMeta;
    }

    public function createOrganization($nameOrganization,$apiKey)
    {
        $uri = "https://api.moysklad.ru/api/remap/1.2/entity/organization";
        $client = new ApiClientMC($uri,$apiKey);
        $organization = [
            "name" => $nameOrganization,
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

    public function getOrganizationNameById($organizationId,$apiKey)
    {
        $uri = "https://api.moysklad.ru/api/remap/1.2/entity/organization/".$organizationId;
        $client = new ApiClientMC($uri,$apiKey);
        $json = $client->requestGet();
        return $json->name;
    }

    public function getOrganizationAccountByNumber($organizationId,$number,$apiKey)
    {
        $uri = "https://api.moysklad.ru/api/remap/1.2/entity/organization/".$organizationId."/accounts";
        $client = new ApiClientMC($uri,$apiKey);
        $json = $client->requestGet();
        $foundedMeta = null;
        foreach($json->rows as $row){
            if($row->accountNumber == $number){
                $foundedMeta = $row->meta;
                break;
            }
        }
        return [
            "meta" => $foundedMeta,
        ];
    }

}
