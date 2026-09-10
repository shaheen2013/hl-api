<?php

namespace App\Services\SocialMedia;

use App\Repositories\SocialMedia\SocialMediaRepository;
use App\Repositories\SocialMedia\SocialMediaShareRepository;
use App\Repositories\Visits\UserBrandRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SocialMediaService
{
    /**
     * @var SocialMediaRepository
     */
    protected $socialMediaRepository;
    /**
     * @var SocialMediaShareRepository
     */
    protected $socialMediaShareRepository;
    /**
     * @var UserBrandRepository
     */
    protected $userBrandRepository;

    /**
     * SocialMediaService constructor.
     * @param SocialMediaRepository $socialMediaRepository
     * @param SocialMediaShareRepository $socialMediaShareRepository
     * @param UserBrandRepository $userBrandRepository
     */
    public function __construct(
        SocialMediaRepository $socialMediaRepository,
        SocialMediaShareRepository $socialMediaShareRepository,
        UserBrandRepository $userBrandRepository
    ) {
        $this->socialMediaRepository = $socialMediaRepository;
        $this->socialMediaShareRepository = $socialMediaShareRepository;
        $this->userBrandRepository = $userBrandRepository;
    }

    /**
     * @param $brandID
     * @param $userID
     * @param $socialMediaName
     * @param $shareTypeID
     */
    public function saveSocialMediaShare($brandID, $userID, $socialMediaName, $shareTypeID)
    {
        $userBrand = $this->userBrandRepository->get($brandID, $userID);
        $socialMedia = $this->socialMediaRepository->getByName($socialMediaName);

        if ($userBrand && $socialMedia && $shareTypeID) {
            return $this->socialMediaShareRepository->create($userBrand->id, $socialMedia->id, Carbon::now(), $shareTypeID);
        }

        Log::warning("Error saving social media share", ["userBrand" => $userBrand, "socialMedia" => $socialMedia, "shareTypeID" => $shareTypeID]);
    }
}
