<?php

namespace App\Http\Controllers\Config\Vendor;

use App\Http\Controllers\Controller;
use App\Models\personal;
use App\Http\Controllers\Config\Vendor\AppInstance;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\Pure;

class VendorEndpointController extends Controller
{
    public function Activate()
    {

        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['PATH_INFO'];


        $pp = explode('/', $path);
        $n = count($pp);
        $appId = $pp[$n - 2];
        $accountId = $pp[$n - 1];

        personal::firstOrCreate([
            'path' => $path,],[
                'path' => $path,
                'appId' => $appId,
                'accountId' => $accountId,
            ]

        );

        $app = AppInstance::load($appId, $accountId);
        $replyStatus = true;

        switch ($method) {
            case 'PUT':
                $requestBody = file_get_contents('php://input');


                $data = json_decode($requestBody);

                $appUid = $data->appUid;
                $accessToken = $data->access[0]->access_token;


                if (!$app->getStatusName()) {
                    $app->accessToken = $accessToken;
                    $app->status = AppInstance::SETTINGS_REQUIRED;
                    $app->persist();
                }
                break;
            case 'GET':
                break;
            case 'DELETE':
                $app->delete();
                $replyStatus = false;
                break;
        }

        if (!$app->getStatusName()) {
            http_response_code(404);
        } else if ($replyStatus) {
            header("Content-Type: application/json");
            echo '{"status": "' . $app->getStatusName() . '"}';
        }


    }
}
