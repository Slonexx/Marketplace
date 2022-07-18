<?php


namespace App\Http\Controllers\Config\Vendor;

use App\Http\Controllers\Controller;

class GlobalVariables extends Controller
{
   public $path;

    /**
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }



}
