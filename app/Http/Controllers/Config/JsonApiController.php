<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JsonApiController extends Controller
{

    private $accessToken;

    function __construct(string $accessToken) {
        $this->accessToken = $accessToken;
    }

    function stores() {
        return makeHttpRequest(
            'GET',
            cfg()->moyskladJsonApiEndpointUrl . '/entity/store',
            $this->accessToken);
    }

    function getObject($entity, $objectId) {
        return makeHttpRequest(
            'GET',
            cfg()->moyskladJsonApiEndpointUrl . "/entity/$entity/$objectId",
            $this->accessToken);
    }


    function jsonApi(): JsonApiController {
        if (!$GLOBALS['jsonApi']) {
            $GLOBALS['jsonApi'] = new JsonApiController(AppInstance::get()->accessToken);
        }
        return $GLOBALS['jsonApi'];
    }

}
