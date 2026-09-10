<?php

namespace App\Repositories\Device;

use App\DeviceBlacklist;

class DeviceBlacklistRepository implements DeviceBlacklistRepositoryInterface
{
    protected $device;

    public function __construct(DeviceBlacklist $device)
    {
        $this->device = $device;
    }

    public function get()
    {
        return $this->device->all();
    }
}
