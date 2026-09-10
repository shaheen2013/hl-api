<?php

namespace App\Repositories\SocialMedia;

use App\SocialMediaShare;

class SocialMediaShareRepository implements SocialMediaShareRepositoryInterface
{
    /**
     * @var SocialMediaShare
     */
    protected $socialMediaShare;

    /**
     * SocialMediaShareRepository constructor.
     * @param SocialMediaShare $socialMediaShare
     */
    public function __construct(SocialMediaShare $socialMediaShare)
    {
        $this->socialMediaShare = $socialMediaShare;
    }

    /**
     * @param $userBrandID
     * @param $socialMediaID
     * @param $date
     * @param $shareTypeID
     */
    public function create($userBrandID, $socialMediaID, $date, $shareTypeID)
    {
        $this->socialMediaShare->create([
            "user_brand_id" => $userBrandID,
            "social_media_id" => $socialMediaID,
            "date" => $date,
            "share_type_id" => $shareTypeID,
        ]);
    }

    /**
     * @param int $userBrandId
     * @return int
     */
    public function deleteByUserBrandId(int $userBrandId): int
    {
        return $this->socialMediaShare
            ->where('user_brand_id', $userBrandId)
            ->delete();
    }
}
