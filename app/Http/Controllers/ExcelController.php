<?php

namespace App\Http\Controllers;

use App\Exports\ProductExport;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function getProductsExcel(Request $request)
    {
        // $request->validate([
        //     'token' => 'required|string'
        // ]);

        // $uri = "https://online.moysklad.ru/api/remap/1.2/entity/product";
        // $apiKey = $request->token;
        // $headers = [
        //     //'Accept' => 'application/json',
        //     'Authorization' => $apiKey,
        // ];
        // $client = new Client();

        // $res = $client->request('GET', $uri ,[
        //     'headers' => $headers,
        // ]);

        // $data = json_decode($res->getBody());

        // //dd($data);

        // foreach ($data->rows as $row) {

        // }

            $arr = array();
            for ($i=0; $i < 2; $i++) { 
               $d['name'] = 'MyName-'.$i;
               $d['cost'] = 223*($i+1);
               array_push($arr,$d);
            }

            

            $export = new ProductExport([
                $arr
            ]);
        
            return Excel::download($export, 'products.xlsx');

    }
}
