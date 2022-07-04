<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class OrderController extends Controller
{
    public function getOrders(Request $request)
    {
        $request->validate([
            'bearer token MySklad' => 'required|string'
        ]);



    }
}
