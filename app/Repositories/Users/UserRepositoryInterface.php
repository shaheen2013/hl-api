<?php

namespace App\Repositories\Users;

use App\Brand;
use App\User;

interface UserRepositoryInterface
{
    /**
     * @param int $userId
     * @return mixed
     */
    public function getById(int $userId);

    /**
     * @param float $sendex
     * @return mixed
     */
    public function getByLowerSendexLevel(float $sendex);

    /**
     * @param array $data
     * @return mixed
     */
    public function createUser(array $data);

    /**
     * @param array $user
     * @param string $pms_id
     * @param int $customer
     * @param int $brand_id
     * @return void
     */
    public function attachUserToHotel(array $user, ?string $pms_id, ?int $customer, int $unsubscribed, int $commercial_profile, int $brand_id);

    /**
     * @param array $user
     * @param int $brand_id
     * @return void
     */
    public function updateUserHotelAttach(array $user, int $brand_id, int $unsubscribed, int $commercial_profile);

    /**
     * @param array $user
     * @param int $brand_id
     * @return array
     */
    public function create(array $user, int $brand_id);

    /**
     * @param array $user
     * @param int $brand_id
     * @return array
     */
    public function update(array $user, int $brand_id);

    /**
     * @param int $userId
     * @param $generation
     * @return mixed
     */
    public function updateGeneration(int $userId, $generation);

    /**
     * @param int $userId
     * @return mixed
     */
    public function delete(int $userId);

    /**
     * @param string $email
     * @return mixed
     */
    public function getUserInfoByEmail(string $email);

    /**
     * @param string $type,
     * @param bool $subscribed
     * @param Date $date
     * @return mixed
     */
    public function generateSubscription($type, $subscribed, $date);
}
