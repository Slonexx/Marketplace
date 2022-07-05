<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\KaspiApiClient;

class OrderController extends Controller
{
    public function getOrders(Request $request)
    {
        // $request->validate([
        //     'token' => 'required|string'
        // ]);

        $uri = "https://kaspi.kz/shop/api/v2/orders?page[number]=0&page[size]=20&filter[orders][state]=ARCHIVE&filter[orders][creationDate][\$ge]=1656688175000&filter[orders][creationDate][\$le]=1657033775000";
        $apiKey = "Oiau+82MUNfcUYPQG9rEyzec3H34OwI5SQ+w6ToodIM=";

        $client = new KaspiApiClient($uri,$apiKey);
        $json = $client->requestGet(true);
        
        $orders = [];
        foreach($json->data as $key=>$row){
            $order = null;
            $order['id'] = $row->id;
            $order['state'] = $row->attributes->state;
            $order['status'] = $row->attributes->status;
            $order['totalPrice'] = $row->attributes->totalPrice;
            $order['customer'] = $row->attributes->customer;
            $order['kaspi_link'] = $row->links->self;
            array_push($orders, $order);
        }

        return $orders;

    }
}
