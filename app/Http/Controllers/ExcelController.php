<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function getProductsExcel(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/product";
        $apiKey = $request->token;
        $headers = [
            //'Accept' => 'application/json',
            'Authorization' => $apiKey,
        ];
        $client = new Client();

        $res = $client->request('GET', $uri ,[
            'headers' => $headers,
        ]);

        $data = json_decode($res->getBody());

        //dd($data);

        foreach ($data->rows as $row) {

        }

       return Excel::create('Report2016', function($excel) {

            // Set the title
            $excel->setTitle('My awesome report 2016');
            // Chain the setters
            $excel->setCreator('Me')->setCompany('Our Code World');

            $excel->setDescription('Export Excel file from API');

            $excel->sheet('Sheetname', function($sheet) {

                $sheet->fromArray(array(
                    array('data1', 'data2'),
                    array('data3', 'data4')
                ));
        
            });

        })->download('xlsx');

    }
}
