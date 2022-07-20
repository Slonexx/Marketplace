<?php

namespace App\Http\Controllers\Config\Vendor;

use App\Http\Controllers\Config\Lib\AppInstanceContoller;
use App\Http\Controllers\Controller;
use App\Models\personal;
use App\Http\Controllers\Config\Vendor\AppInstance;


class VendorEndpointController extends Controller
{
    public function Activate()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['PATH_INFO'];
        $this->downloadJSONFile($path);


        $pp = explode('/', $path);
        $n = count($pp);
        $appId = $pp[$n - 2];
        $accountId = $pp[$n - 1];

        $app = AppInstanceContoller::load($appId, $accountId);
        $replyStatus = true;

        switch ($method) {
            case 'PUT':
                $requestBody = file_get_contents('php://input');

                $data = json_decode($requestBody);

                $appUid = $data->appUid;
                $accessToken = $data->access[0]->access_token;

                if (!$app->getStatusName()) {
                    $app->accessToken = $accessToken;
                    $app->status = AppInstanceContoller::SETTINGS_REQUIRED;
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

    public function downloadJSONFile($message){
        $data = json_encode([$message]);
        $file = time() .rand(). '_file.json';
        $destinationPath=public_path()."/upload/";
        if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
        File::put($destinationPath.$file,$data);
        return response()->download($destinationPath.$file);
    }
}
