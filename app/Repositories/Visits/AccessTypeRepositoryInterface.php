<?php

/**
 * Created by PhpStorm.
 * User: jmatesanz
 * Date: 04/11/2019
 * Time: 12:10
 */

namespace App\Repositories\Visits;

interface AccessTypeRepositoryInterface
{
    /**
     * @param $name
     * @return mixed
     */
    public function get($name);
}
