<?php

namespace App\Events;

use App\Events\UserEvent;
use Hotelinking\Services\HLConnectionInterface;
use App\Services\SocialMedia\SocialMediaService;
use Illuminate\Support\Arr;

class SocialMediaShare extends UserEvent
{
    protected $brandID;
    private $userID;
    private $shareType;
    private $socialMedia;

    protected $userRepository;

    /**
     * Create a new event instance.
     * @param $payload
     * @param HLConnectionInterface $connection
     */
    public function __construct($payload, HLConnectionInterface $connection)
    {
        $this->brandID = Arr::get($payload, 'payload.brand.id');
        $this->userID = Arr::get($payload, 'payload.user.id');
        $this->shareType = Arr::get($payload, 'payload.share.type');
        $this->socialMedia = Arr::get($payload, 'payload.share.socialMedia');
        $this->socialMediaService = app('App\Services\SocialMedia\SocialMediaService');

        $brandService = app('App\Services\BrandService');

        parent::__construct($payload, $connection, $brandService);
    }

    public function save()
    {
        $this->socialMediaService->saveSocialMediaShare($this->brandID, $this->userID, $this->socialMedia, $this->shareType);
    }
}
