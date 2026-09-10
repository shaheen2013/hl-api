<?php

namespace App\Repositories\Visits;

use App\Device;

class DeviceRepository implements DeviceRepositoryInterface
{
    /**
     * @var Device
     */
    protected $device;

    /**
     * DeviceRepository constructor.
     * @param Device $device
     */
    public function __construct(Device $device)
    {
        $this->device = $device;
    }

    /**
     * @param $macAddress
     * @param $family
     * @param $brand
     * @param $model
     * @return mixed
     */
    public function firstOrCreate($macAddress, $family, $brand, $model)
    {
        return $this->device->firstOrCreate(
            [
                'mac_address' => $macAddress
            ],
            [
                "device_family" => $family,
                "device_brand" => $brand,
                "device_model" => $model,
            ]
        );
    }
}
