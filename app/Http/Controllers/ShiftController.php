<?php

namespace App\Http\Controllers;

use App\Services\shift\ShiftService;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    private ShiftService $shiftService;

    /**
     * @param ShiftService $shiftService
     */
    public function __construct(ShiftService $shiftService)
    {
        $this->shiftService = $shiftService;
    }

    public function closeShift(Request $request){
        $data = $request->validate([
            "accountId" => "required|string",
            "pincode" => "required|string",
        ]);

        $serviceRes = $this->shiftService->closeShift($data);
        return response($serviceRes["res"],$serviceRes["code"]);
    }

}
