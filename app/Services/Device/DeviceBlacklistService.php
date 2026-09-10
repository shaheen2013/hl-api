<?php

namespace App\Services\Device;

use App\Repositories\Device\DeviceBlacklistRepositoryInterface;

class DeviceBlacklistService
{
    protected $deviceBlacklist;

    public function __construct(DeviceBlacklistRepositoryInterface $deviceBlacklist)
    {
        $this->deviceBlacklist = $deviceBlacklist;
    }

    public function get()
    {
        return $this->deviceBlacklist->get();
    }
}
