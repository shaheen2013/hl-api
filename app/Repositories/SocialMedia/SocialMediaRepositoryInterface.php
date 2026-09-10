<?php

/**
 * Created by PhpStorm.
 * User: jmatesanz
 * Date: 04/11/2019
 * Time: 10:29
 */

namespace App\Repositories\SocialMedia;

interface SocialMediaRepositoryInterface
{
    /**
     * @param $socialMedia
     * @return mixed
     */
    public function getByName($socialMedia);
}
