<?php

/**
 * Created by PhpStorm.
 * User: jmatesanz
 * Date: 04/11/2019
 * Time: 10:30
 */

namespace App\Repositories\SocialMedia;

interface SocialMediaShareRepositoryInterface
{
    /**
     * @param $userBrandID
     * @param $socialMediaID
     * @param $date
     * @param $shareTypeID
     */
    public function create($userBrandID, $socialMediaID, $date, $shareTypeID);

    /**
     * @param int $userBrandId
     * @return int
     */
    public function deleteByUserBrandId(int $userBrandId): int;
}
