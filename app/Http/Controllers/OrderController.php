<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiClientMC;
use App\Http\Controllers\KaspiApiClient;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PositionController;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class OrderController extends Controller
{
    public function getOrdersFromKaspi($apiKey, $urlKaspi)
    {
        // $request->validate([
        //     'tokenKaspi' => 'required|string'
        // ]);

        //$uri = "https://kaspi.kz/shop/api/v2/orders?page[number]=0&page[size]=20&filter[orders][state]=ARCHIVE&filter[orders][creationDate][\$ge]=1657533973000&filter[orders][creationDate][\$le]=1658138773000";
        //$apiKey = "Oiau+82MUNfcUYPQG9rEyzec3H34OwI5SQ+w6ToodIM=";

        $client = new KaspiApiClient($urlKaspi,$apiKey);
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
                //dd($jsonProduct1);
                $uri = $jsonProduct1->data->relationships->merchantProduct->links->related;
                $client->setRequestUrl($uri);
                $jsonProduct2 = $client->requestGet(true);
                //dd($jsonProduct2);
                if($jsonProduct2->data == null){
                    $product_ = [
                        "code" => $jsonProduct1->data->attributes->code,
                        "name" => $jsonProduct1->data->attributes->name,
                        "manufacturer" => "",
                    ];
                    $entry['product'] = (object) $product_;
                } else {
                    $entry['product'] = $jsonProduct2->data->attributes;
                }
                

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


    public function getOrdersNotInserted($tokenMs,$tokenKaspi, $urlKaspi)
    {
        $ordersFromKaspi = $this->getOrdersFromKaspi($tokenKaspi, $urlKaspi);
        //app(ProductController::class)->insertProducts($tokenMs,$ordersFromKaspi);
        $ordersFromMs = $this->getOrdersFromMS($tokenMs);
        
        $notAddedOrders = [];
        foreach($ordersFromKaspi as $order){
            //dd($order["id"]);
            if (in_array($order["id"],$ordersFromMs) == false) {
               array_push($notAddedOrders,$order);
            }
        }

        return $notAddedOrders;
    }

    public function insertOrders(Request $request)
    {
        $request->validate([
            'tokenKaspi' => 'required|string',
            'tokenMs' => 'required|string',
            'payment_option' => 'required|integer',
            'demand_option' => 'required|integer',
            'state' => 'required|string',
            'fdate' => 'required|string',
            'sdate' => 'required|string',
            'organization_id' => 'required|string',
            'project_name' => 'sometimes|required|string',
            'sale_channel_name' => 'sometimes|required|string',
            'organization_account_number' => 'sometimes|required|string',
        ]);

        $paymentOption = $request->payment_option;
        $demandOption = $request->demand_option;

        $organization_name = app(OrganizationController::class)->getOrganizationNameById($request->organization_id,$request->tokenMs);
        $project_name = $request->project_name;
        $sale_channel_name = $request->sale_channel_name;
        $organization_account = [
            "organization_id" => $request->organization_id,
            "number_account" => $request->organization_account_number,
        ];

        $fdate = app(TimeFormatController::class)->getMilliseconds($request->fdate);
        $sdate =  app(TimeFormatController::class)->getMilliseconds($request->sdate);

        $urlKaspi = "https://kaspi.kz/shop/api/v2/orders?page[number]=0&page[size]=20&filter[orders][state]=".
        $request->state."&filter[orders][creationDate][\$ge]=".
        $fdate."&filter[orders][creationDate][\$le]=".$sdate;

        $orders = $this->getOrdersNotInserted($request->tokenMs,$request->tokenKaspi,$urlKaspi);

        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/customerorder";
        $client = new ApiClientMC($uri,$request->tokenMs);

        $count = 0;
        foreach ($orders as $order) {
            $formattedOrder = $this->mapOrderToAdd(
                $order,
                $sale_channel_name,
                $project_name,
                $organization_name,
                $organization_account,
                $request->tokenMs
             );
            $createdOrder = $client->requestPost($formattedOrder);
            $orderStatus = $order['status'];
            $this->setPositions($createdOrder->id,$orderStatus,$order['entries'],$request->tokenMs);
            app(DocumentController::class)->initDocuments($order['entries'],$orderStatus,$createdOrder->meta,
            $paymentOption,$demandOption,$formattedOrder,$request->tokenMs);
            $count++;
        }

        return response([
            "mesage" => "Inserted orders:".$count,
        ]);
    }

    private function mapOrderToAdd(
        $order,$sale_channel_name,$project_name,
        $organization_name,$organization_account,$apiKey)
    {
            //$formattedOrders = [];
            $formattedOrder = null;
            $address = null;
            foreach ($order['entries'] as $entry) {
                $address = $entry['address']->formattedAddress;
                $formattedOrder['shipmentAddressFull'] = ["addInfo" => $address];
            }
            $formattedOrder['agent'] = $this->getAgent($order['customer'], $address,$apiKey);
            $formattedOrder['organization'] = app(OrganizationController::class)->getKaspiOrganization($organization_name,$apiKey);
            $formattedOrder['rate'] = [
                "currency" => app(CurrencyController::class)->getKzCurrency($apiKey),
            ];
            $formattedOrder['store'] = app(StoreController::class)->getKaspiStore($apiKey);
            $formattedOrder['externalCode'] = $order['id'];
            $formattedOrder['state'] = $this->getState($order['status'],$apiKey);

            $info = "https://kaspi.kz/merchantcabinet/#/orders/details/".$order['code'];
            $formattedOrder['description'] = "Order code: ".$order['code'].". More info: ".$info;

            $attributes = app(OrderAttributesController::class)->getAttributeDelivery($order['state'],$apiKey);
            $formattedOrder['attributes'] = [
                0 => [
                    "meta" => $attributes["meta"],
                    "name" => "Способ доставки",
                    "value" => $attributes["value"],
                ],
            ];

            if($sale_channel_name != null) {
                $formattedOrder['salesChannel'] = app(SalesChannelController::class)->getSaleChannel($sale_channel_name,$apiKey);
            }

            if($project_name != null){
                $formattedOrder['project'] = app(ProjectController::class)->getProject($project_name,$apiKey);
            }

            if($organization_account["number_account"] != null){
                $formattedOrder['organizationAccount'] = app(OrganizationController::class)
                ->getOrganizationAccountByNumber($organization_account["organization_id"],
                $organization_account["number_account"],$apiKey);
            }
            //$formattedOrder['positions'] = $this->getPositions($order['entries'],$apiKey);
            //array_push($formattedOrders, $formattedOrder);

        return $formattedOrder;
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
    
    private function setPositions($orderId,$orderStatus,$entries,$apiKey)
    {
        app(PositionController::class)->setPositions($orderId,$orderStatus,$entries,$apiKey);
    }

    private function getOrdersKaspiWithStatus($apiKey, $urlKaspi)
    {
        $ordersFromKaspi = $this->getOrdersFromKaspi($apiKey,$urlKaspi);
        //dd($jsonAllOrders);
        foreach($ordersFromKaspi as $row => $k){
            $st['statusOrder'] = app(StatusController::class)->getStatusName($k['status']);
           // array_push($ordersFromKaspi[$row],$st['statusOrder']);
           $ordersFromKaspi[$row] = $k+$st;
        }

        //dd($ordersFromKaspi);

        return $ordersFromKaspi;
    }

    private function getOrdersMsWithStatus($apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/customerorder";
        $client = new ApiClientMC($uri,$apiKey);
        $jsonAllOrders = $client->requestGet();
        //dd($jsonAllOrders);
        $ordersFromMs = [];
        foreach ($jsonAllOrders->rows as $key => $row) {
            $status = $this->getStatusNameByMeta($row->state->meta->href,$apiKey);
            $order['id'] = $row->id;
            $order["externalCode"] =  $row->externalCode;
            $order['meta'] = $row->meta;
            $order["status"] = $status;
            if (property_exists($row,'positions') == true) {
                $order['positions'] = $row->positions;
            } else {
                $order['positions'] = null;
            }
            
            if(property_exists($row,'payments') == true){
                $order['payments'] = $row->payments;
            } else {
                $order['payments'] = null;
            }

            if(property_exists($row,'demands') == true){
                $order['demands'] = $row->demands;
            } else {
                $order['demands'] = null;
            }

            array_push($ordersFromMs,$order);
        }
        return $ordersFromMs;
    }

    private function getStatusNameByMeta($uri,$apiKey)
    {
        $client = new ApiClientMC($uri,$apiKey);
        $jsonStatus = $client->requestGet();
        return $jsonStatus->name;
    }

    public function changeOrderStatus(Request $request)
    {
        $request->validate([
            'tokenKaspi' => 'required|string',
            'tokenMs' => 'required|string',
            'payment_option' => 'required|integer',
            'demand_option' => 'required|integer',
            'state' => 'required|string',
            'fdate' => 'required|string',
            'sdate' => 'required|string',
            'organization_id' => 'required|string',
            'project_name' => 'sometimes|required|string',
            'sale_channel_name' => 'required|string',
            'organization_account_number' => 'sometimes|required|string',
        ]);

        $paymentOption = $request->payment_option;
        $demandOption = $request->demand_option;

        $organization_name = app(OrganizationController::class)->getOrganizationNameById($request->organization_id,$request->tokenMs);
        $project_name = $request->project_name;
        $sale_channel_name = $request->sale_channel_name;
        $organization_account = [
            "organization_id" => $request->organization_id,
            "number_account" => $request->organization_account_number,
        ];

        $fdate = app(TimeFormatController::class)->getMilliseconds($request->fdate);
        $sdate =  app(TimeFormatController::class)->getMilliseconds($request->sdate);

        $urlKaspi = "https://kaspi.kz/shop/api/v2/orders?page[number]=0&page[size]=20&filter[orders][state]=".
        $request->state."&filter[orders][creationDate][\$ge]=".
        $fdate."&filter[orders][creationDate][\$le]=".$sdate;

        $ordersFromKaspi = $this->getOrdersKaspiWithStatus($request->tokenKaspi, $urlKaspi);
        $ordersFromMs = $this->getOrdersMsWithStatus($request->tokenMs);

        //dd($ordersFromKaspi);

        $count = 0;
        foreach($ordersFromKaspi as $orderKaspi){
            foreach($ordersFromMs as $orderMs){
                if($orderKaspi['id'] == $orderMs['externalCode']) {
                    if($orderKaspi['statusOrder'] != $orderMs['status']){
                        $metaState = app(StateController::class)->getState($orderKaspi['status'],$request->tokenMs);
                        $this->changeOrderStatusMs($orderMs['id'],$metaState,$request->tokenMs);
                        $formattedOrder = $this->mapOrderToAdd(
                            $orderKaspi,
                            $sale_channel_name,
                            $project_name,
                            $organization_name,
                            $organization_account,
                            $request->tokenMs
                         );
                        app(DocumentController::class)->createDocuments(
                            $orderMs['payments'], $orderMs['demands'],
                            $orderKaspi['entries'],$orderKaspi['status'],
                            $orderMs['meta'],$paymentOption,$demandOption,
                            $formattedOrder,$request->tokenMs
                        );
                        if($orderMs['positions'] != null){
                            $client = new ApiClientMC($orderMs['positions']->meta->href,$request->tokenMs);
                            $jsonPosition = $client->requestGet();
                            foreach($jsonPosition->rows as $row){
                                $orderId = $orderMs['id'];
                                $positionId = $row->id;
                                $quantity = $row->quantity;
                                if($orderKaspi['status'] == 'ACCEPTED_BY_MERCHANT'){
                                    app(PositionController::class)->setPositionReserve($orderId, $positionId,
                                     $quantity,$request->tokenMs);
                                } else {
                                    if($row->reserve > 0){
                                        app(PositionController::class)->setPositionReserve($orderId, $positionId, 0,
                                        $request->tokenMs);
                                    }
                                }
                            }
                        }
                        $count++;
                    }
                }
            }
        }

        return response([
            "message" => "Updated order status: ".$count,
        ]);

    }

    private function changeOrderStatusMs($orderId,$metaState, $apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/customerorder/".$orderId;
        $client = new ApiClientMC($uri,$apiKey);
        $client->requestPut([
            "state" => [
                "meta" => $metaState,
            ],
        ]);
    }

    // private function getContentJson($filename)
    // {
    //     $path = public_path().'/json'.'/'.$filename.'.json';
    //     return json_decode(file_get_contents($path),true);
    // }

}
