<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiClientMC;
use App\Http\Controllers\KaspiApiClient;

class OrderController extends Controller
{
    public function getOrdersFromKaspi($apiKey)
    {
        // $request->validate([
        //     'tokenKaspi' => 'required|string'
        // ]);

        $uri = "https://kaspi.kz/shop/api/v2/orders?page[number]=0&page[size]=20&filter[orders][state]=ARCHIVE&filter[orders][creationDate][\$ge]=1656688175000&filter[orders][creationDate][\$le]=1657111171000";
        //$apiKey = "Oiau+82MUNfcUYPQG9rEyzec3H34OwI5SQ+w6ToodIM=";

        $client = new KaspiApiClient($uri,$apiKey);
        $jsonAllOrders = $client->requestGet(true);
        
        $ordersFromKaspi = [];
        //dd($jsonAllOrders);
        foreach($jsonAllOrders->data as $key=>$row){
            $order = null;
            $order['id'] = $row->id;
            $order['code'] = $row->attributes->code;
            $order['state'] = $row->attributes->state;
            $order['status'] = $row->attributes->status;
            $order['totalPrice'] = $row->attributes->totalPrice;
            $order['customer'] = $row->attributes->customer;
            $order['link_self'] = $row->links->self;

            $uri = $row->relationships->entries->links->related;
            $client->setRequestUrl($uri);
            $jsonEntry = $client->requestGet(true);

            $entriesOfOrder = [];

            foreach($jsonEntry->data as $key=>$row2){
                $entry = null;
                $entry['id'] = $row2->id;
                $entry['basePrice'] = $row2->attributes->basePrice;
                $entry['totalPrice'] = $row2->attributes->totalPrice;
                $entry['quantity'] = $row2->attributes->quantity;
                $entry['type'] = $row2->attributes->unitType;

                $uri = $row2->relationships->product->links->related;
                $client->setRequestUrl($uri);
                $jsonProduct1 = $client->requestGet(true);

                $uri = $jsonProduct1->data->relationships->merchantProduct->links->related;
                $client->setRequestUrl($uri);
                $jsonProduct2 = $client->requestGet(true);

                $entry['product'] = $jsonProduct2->data->attributes;

                $uri = $row2->relationships->deliveryPointOfService->links->related;
                $client->setRequestUrl($uri);
                $jsonAddress = $client->requestGet(true);
                $entry['address'] = $jsonAddress->data->attributes->address;

                array_push($entriesOfOrder, $entry);
            }

            $order['entries'] = $entriesOfOrder;

            array_push($ordersFromKaspi, $order);
        }

        return $ordersFromKaspi;
    }

    public function getOrdersFromMS($apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/customerorder";
        $client = new ApiClientMC($uri,$apiKey);
        $jsonAllOrders = $client->requestGet();
        //dd($jsonAllOrders);
        $ordersFromMs = [];
        $count = 0;
        foreach ($jsonAllOrders->rows as $key => $row) {
            $ordersFromMs[$count] = $row->externalCode;
            $count++;
        }
        return $ordersFromMs;
    }


    public function getOrdersNotInserted($tokenMs,$tokenKaspi)
    {
        $ordersFromKaspi = $this->getOrdersFromKaspi($tokenKaspi);
        //app(ProductController::class)->insertProducts($tokenMs,$ordersFromKaspi);
        $ordersFromMs = $this->getOrdersFromMS($tokenMs);
        
        $notAddedOrders = [];
        foreach($ordersFromKaspi as $order){
            //dd($order["id"]);
            if (in_array($order["id"],$ordersFromMs) == false) {
               array_push($notAddedOrders,$order);
            }
        }

        return $this->mapOrdersToAdd($notAddedOrders,$tokenMs);
    }

    public function insertOrders(Request $request)
    {
        $request->validate([
            'tokenKaspi' => 'required|string',
            'tokenMs' => 'required|string',
        ]);

        $orders = $this->getOrdersNotInserted($request->tokenMs,$request->tokenKaspi);

        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/customerorder";
        $client = new ApiClientMC($uri,$request->tokenMs);

        foreach ($orders as $order){
            $client->requestPost($order);
        }
        return response([
            "mesage" => "Inserted orders:".count($orders),
        ]);
    }

    private function mapOrdersToAdd($orders,$apiKey)
    {
        $formattedOrders = [];
        foreach ($orders as $order){
             $formattedOrder = null;
             $address = null;
             foreach ($order['entries'] as $entry) {
                $address = $entry['address']->formattedAddress;
                $formattedOrder['shipmentAddressFull'] = ["addInfo" => $address];
            }
            $formattedOrder['agent'] = $this->getAgent($order['customer'], $address,$apiKey);
            $formattedOrder['organization'] = $this->getContentJson('organization');
            $formattedOrder['rate'] = $this->getContentJson('rate');
            $formattedOrder['store'] = $this->getContentJson('store');
            $formattedOrder['externalCode'] = $order['id'];
            $formattedOrder['state'] = $this->getState($order['status'],$apiKey);
            //$formattedOrder['positions'] = $this->getPositions($order['entries'],$apiKey);
            array_push($formattedOrders, $formattedOrder);
        }

        return $formattedOrders;
    }

    private function getAgent($customer, $address,$apiKey)
    {
       $meta = app(AgentController::class)->getAgent($customer,$address,$apiKey);
       $res = [
            "meta" => [
                "href" => $meta->href,
                "type" => $meta->type,
                "mediaType" => $meta->mediaType,
            ],
       ];
       return $res;
    }


    # APPROVED_BY_BANK – одобрен банком
    #CANCELLED – отменён
    #CANCELLING – ожидает отмены
    #ACCEPTED_BY_MERCHANT– принят на обработку продавцом
    #COMPLETED – завершён  
    private function getState($status,$apiKey)
    {
       $meta = app(StateController::class)->getState($status,$apiKey);
       $res = [
            "meta" => [
                "href" => $meta->href,
                "type" => $meta->type,
                "mediaType" => $meta->mediaType,
            ],
        ];
        return $res;
    }
    
    private function getPositions($entries,$apiKey)
    {

    }

    private function getContentJson($filename) {
        $path = public_path().'/json'.'/'.$filename.'.json';
        return json_decode(file_get_contents($path),true);
    }

}
