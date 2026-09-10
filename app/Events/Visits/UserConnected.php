<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 26/07/2019
 * Time: 10:57
 */

namespace App\Events;

use Hotelinking\Services\HLConnectionInterface;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use UAParser\Parser;
use App\Visit;
use App\UserBrand;

class UserConnected extends HLEvent
{
    /**
     * Create a new event instance.
     *
     * @param $payload
     * @param HLConnectionInterface $connection
     */
    public function __construct(
        $payload,
        HLConnectionInterface $connection
    ) {
        $this->user_id = Arr::get($payload, 'payload.user.id');
        $this->isClient = (int) Arr::get($payload, 'payload.user.is_client');
        $this->brand_id = Arr::get($payload, 'payload.brand.id');
        $this->macAddress = Arr::get($payload, 'payload.device.mac');
        $this->userAgent = Arr::get($payload, 'payload.device.userAgent');
        $this->acceptLanguage = Arr::get($payload, 'payload.device.acceptLanguage');
        $this->accessType = Arr::get($payload, 'payload.user.source');
        $this->accessCode = Arr::get($payload, 'payload.roomID');
        $this->reservation = Arr::get($payload, 'payload.pms_reservation');
        $this->brandService = app('App\Services\BrandService');
        $this->visitService = app('App\Services\Visits\VisitService');
        $this->userService = app('App\Services\Users\UserService');

        parent::__construct($payload, $connection);
    }

    public function getStay()
    {
        $stayTime = $this->brandService->getStayTime($this->brand_id);
        $stay['check_in'] = Carbon::now();
        $stay['check_out'] = Carbon::now()->addDays($stayTime);
        $stay['is_client'] = $this->isClient;

        return (object)$stay;
    }

    /**
     * @return \Illuminate\Cache\CacheManager|mixed
     * @throws \Exception
     */
    public function getUser()
    {
        return $this->userService->getUser($this->user_id);
    }

    public function getBrand()
    {
        return $this->brandService->getBrand($this->brand_id);
    }

    public function getVisit()
    {
        return $this->visitService->getVisit($this->getUserBrand()->id, Carbon::now());
    }

    public function storeVisit(UserBrand $userBrand, \stdClass $stay, array $reservation = null)
    {
        return $this->visitService->storeVisit($userBrand, $stay, $reservation);
    }

    /**
     * Update visit with stay info
     *
     * @param Visit $visit
     * @param stdClass $stay
     *
     * @return boolean
     */
    public function updateVisit(Visit $visit, \stdClass $stay): bool
    {
        // Set new check_out on stay object
        $stayTime = $this->brandService->getStayTime($this->brand_id);
        $stay->check_out = Carbon::now()->addDays($stayTime);
        $reservation = $this->getReservation();
        // Update visit
        return $this->visitService->updateVisit($visit, $stay, $reservation);
    }

    public function storeConnection($user, $visit, $stay, $userAgent, $connection)
    {
        return $this->visitService->storeConnection($user->id, $this->brand_id, $visit->id, $stay, $userAgent, $connection);
    }

    public function getUserAgent()
    {
        $parser = Parser::create();
        $userAgentParsed = $parser->parse($this->userAgent);
        $fakeMac = 'NO_MAC_' . md5($this->brand_id . $this->user_id . $userAgentParsed->device->family . $userAgentParsed->device->brand . $userAgentParsed->device->model);

        return [
            "headers"                => $this->userAgent,
            "browser"                => $userAgentParsed->ua->family,
            "browserVersion"         => $userAgentParsed->ua->toVersion(),
            "browserLang"            => $this->acceptLanguage,
            "operatingSystem"        => $userAgentParsed->os->family,
            "operatingSystemVersion" => $userAgentParsed->os->toVersion(),
            "macAddress"             => empty(trim($this->macAddress)) ? $fakeMac : $this->macAddress,
            "deviceFamily"           => $userAgentParsed->device->family,
            "deviceBrand"            => $userAgentParsed->device->brand,
            "deviceModel"            => $userAgentParsed->device->model,
        ];
    }

    public function getConnection()
    {
        return [
            "accessType" => $this->accessType,
            "accessCode" => $this->accessCode
        ];
    }

    public function getUserBrand()
    {
        return $this->userService->getUserBrand($this->brand_id, $this->user_id);
    }

    public function getReservation()
    {
        return $this->reservation;
    }
}
