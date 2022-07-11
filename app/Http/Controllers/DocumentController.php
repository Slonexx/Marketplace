<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function createDocument($metaOrder,$sum,$isPayment,$formattedOrder, $apiKey)
    {
        $uri = null;
        if ($isPayment == true) {
            $uri = "https://online.moysklad.ru/api/remap/1.2/entity/paymentin";
        } else {
            $uri = "https://online.moysklad.ru/api/remap/1.2/entity/cashin";
        }


        $meta  = [
            "href" => $metaOrder->href,
            "metadataHref" =>$metaOrder->metadataHref,
            "type" => $metaOrder->type,
            "mediaType" => $metaOrder->mediaType,
            "uuidHref" => $metaOrder->uuidHref,
        ];

        //dd($metaOrder);

        $client = new ApiClientMC($uri, $apiKey);
        $docBody = [
            "agent" => $formattedOrder['agent'],
            "organization" => $formattedOrder['organization'],
            "rate" => $formattedOrder['rate'],
            "salesChannel" => $formattedOrder['salesChannel'],
            "sum" => $sum*100,
            "operations" => [
                0=> [
                    "meta" => $meta,
                ],
            ],
        ];
        $client->requestPost($docBody);

        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/demand";
        $client->setRequestUrl($uri);
        $docBodyDemand = [
            "agent" => $formattedOrder['agent'],
            "organization" => $formattedOrder['organization'],
            "rate" => $formattedOrder['rate'],
            "store" => $formattedOrder['store'],
            "salesChannel" => $formattedOrder['salesChannel'],
            "addInfo" => $formattedOrder['shipmentAddressFull']['addInfo'],
        ];
        ;$createdDemand = $client->requestPost($docBodyDemand);

        $uri = 'https://online.moysklad.ru/api/remap/1.2/entity/demand'.'/'.$createdDemand->id;
        $client->setRequestUrl($uri);
        $bodyOrder = [
            "customerOrder" => [
                0 => [
                    "meta" => $meta,
                ],
            ],
        ];
        dd($bodyOrder);
        $client->requestPut($bodyOrder);
    }
}
