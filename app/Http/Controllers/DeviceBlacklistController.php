<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Device\DeviceBlacklistService;

class DeviceBlacklistController extends Controller
{
    protected $deviceBlacklistService;

    public function __construct(DeviceBlacklistService $deviceBlacklistService)
    {
        $this->deviceBlacklistService = $deviceBlacklistService;
    }

    /**
     * @SWG\Get(
     *     path="/api/device/blacklist",
     *     description="Gets all devices blacklisted",
     *     tags={"Languages"},
     *     @SWG\Parameter(
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="success",
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing Data"
     *     )
     * )
     *
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->deviceBlacklistService->get();
    }
}
