<?php

namespace App\Repositories\SocialMedia;

use App\SocialMedia;

class SocialMediaRepository implements SocialMediaRepositoryInterface
{
    /**
     * @var SocialMedia
     */
    protected $socialMedia;

    /**
     * SocialMediaRepository constructor.
     * @param SocialMedia $socialMedia
     */
    public function __construct(SocialMedia $socialMedia)
    {
        $this->socialMedia = $socialMedia;
    }

    /**
     * @param $socialMedia
     * @return mixed
     */
    public function getByName($socialMedia)
    {
        return $this->socialMedia->where("name", $socialMedia)->first();
    }
}
