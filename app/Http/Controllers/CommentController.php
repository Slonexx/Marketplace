<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function getCommentsFromKaspi(Request $request)
    {
        $request->validate([
            'tokenKaspi' => 'required|string',
        ]);

        $uri = "";
        $client = new ApiClientMC($uri, $request->tokenKaspi);
        $res = $client->requestGet();

    }
}
