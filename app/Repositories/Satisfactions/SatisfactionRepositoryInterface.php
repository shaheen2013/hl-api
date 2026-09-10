<?php

/**
 * Created by PhpStorm.
 * User: jmatesanz
 * Date: 04/11/2019
 * Time: 10:31
 */

namespace App\Repositories\Satisfactions;

interface SatisfactionRepositoryInterface
{
    /**
     * @param $fromID
     * @param null $toID
     * @return mixed
     */
    public function getFromRangeId($fromID, $toID = null);
}
