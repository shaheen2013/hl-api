<?php

namespace App\Events;

use App\Events\UserEvent;
use Hotelinking\Services\HLConnectionInterface;
use App\Services\Users\UserService;
use App\User;
use Illuminate\Support\Arr;

class CreateUser extends UserEvent
{
    protected $brandID;
    private $user;
    private $source;
    private $gdprAcceptedTime;
    private $facebookID;
    private $facebookFriends;
    private $facebookImage;

    /**
     * Create a new event instance.
     * @param $payload
     * @param HLConnectionInterface $connection
     */
    public function __construct($payload, HLConnectionInterface $connection)
    {
        $this->brandID = Arr::get($payload, 'context.brandID');

        $this->user = new User();
        $this->user->email = Arr::get($payload, 'payload.email');
        $this->user->sendex = Arr::get($payload, 'payload.emailSendex');
        $this->user->email_result = Arr::get($payload, 'payload.emailDeliverability');
        $this->user->nombre = Arr::get($payload, 'payload.firstName') . ' ' . Arr::get($payload, 'payload.lastName');
        $this->user->sexo = Arr::get($payload, 'payload.gender');
        $this->user->location = Arr::get($payload, 'payload.locale');
        $this->user->lang = substr(Arr::get($payload, 'payload.locale'), 0, 2);
        $this->user->fecha_nacimiento = Arr::get($payload, 'payload.birthDate');
        $this->user->pais = get_country_name($this->user->location);

        $this->facebookID = Arr::get($payload, 'payload.facebookID');
        $this->facebookFriends = Arr::get($payload, 'payload.facebookFriends');
        $this->facebookImage = Arr::get($payload, 'payload.facebookImg');
        $this->source = Arr::get($payload, 'payload.origin');
        $this->gdprAcceptedTime = Arr::get($payload, 'payload.gdprAcceptedTime');

        $brandService = app('App\Services\BrandService');

        parent::__construct($payload, $connection, $brandService);
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getUserInfo()
    {
        $userService = app('App\Services\Users\UserService');
        return $userService->getUserInfoByEmail($this->user->email);
    }

    public function getBrandID()
    {
        return $this->brandID;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getGdprAcceptedTime()
    {
        return $this->gdprAcceptedTime;
    }

    public function getFacebookID()
    {
        return $this->facebookID;
    }

    public function getFacebookFriends()
    {
        return $this->facebookFriends;
    }

    public function getFacebookImage()
    {
        return $this->facebookImage;
    }

    public function createGuid()
    {
        $data = openssl_random_pseudo_bytes(16);
        assert(strlen($data) == 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
