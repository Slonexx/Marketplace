<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExpenseItemController extends Controller
{
    public function getExpenseItem($expenseItemName,$apiKey)
    {
        $uri = "https://api.moysklad.ru/api/remap/1.2/entity/expenseitem";
        $client = new ApiClientMC($uri,$apiKey);
        $jsonStates = $client->requestGet();
        $foundedMeta = null;
        foreach($jsonStates->rows as $row) {
            if($row->name == $expenseItemName){
                $foundedMeta= $row->meta;
                break;
            }
        }
        return $foundedMeta;
    }
}
