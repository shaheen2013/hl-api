<?php

namespace App\Repositories\Device;

use App\DeviceBlacklist;

interface DeviceBlacklistRepositoryInterface
{
    public function __construct(DeviceBlacklist $device);

    public function get();
}
