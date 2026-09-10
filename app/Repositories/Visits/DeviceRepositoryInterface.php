<?php

/**
 * Created by PhpStorm.
 * User: jmatesanz
 * Date: 04/11/2019
 * Time: 12:10
 */

namespace App\Repositories\Visits;

interface DeviceRepositoryInterface
{
    /**
     * @param $macAddress
     * @param $family
     * @param $brand
     * @param $model
     * @return mixed
     */
    public function firstOrCreate($macAddress, $family, $brand, $model);
}
