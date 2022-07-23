<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function getProject($projectName,$apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/project?search=".$projectName;
        $client = new ApiClientMC($uri,$apiKey);
        $jsonProjects = $client->requestGet();
        $foundedMeta = null;
        foreach($jsonProjects->rows as $row){
            $foundedMeta = $row->meta;
            break;
        }
        return [
            "meta" => $foundedMeta,
        ];
    }
}
