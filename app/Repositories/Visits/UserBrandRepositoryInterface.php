<?php

namespace App\Repositories\Visits;

use App\Types\Users\ExternalUsersDataType;
use App\User;
use App\UserBrand;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface UserBrandRepositoryInterface
{
    /**
     * @param $userID
     * @param $brandID
     * @return mixed
     */
    public function get($brandID, $userID);

    /**
     * @param $userID
     * @param $brandID
     * @return mixed
     */
    public function unsubscribe($userID, $brandID, $unsubscribeNotifications, $unsubscribeCommercialProfile);

    /**
     * Find user by brandId and userId
     *
     * @param int $brandId
     * @param int $userId
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getUserByBrandIdAndUserId(int $brandId, int $userId);

    /**
     * Get brand users applyng some filters passed on a array $filterList
     * Some of this filters are:
     *  - language: filter users by user lang
     *  - dateFrom: filter users since dateFrom
     *  - dateTo: filter users until dateTo
     *
     *  The paginate filters:
     *  - page: the page wanted
     *  - pageItems: number of items per page
     *
     * @param int $parentBrandId - the brand id from chain or hotel in case of independent hotel.
     * @param int $brandIds - the brand ids to be search users.
     * @param ExternalUsersDataType $externalUsersDataType - the list of filters.
     *
     * @return LengthAwarePaginator
     */
    public function getPaginatedAnswerWithFilters(int $parentBrandId, array $brandIds, ExternalUsersDataType $externalUsersDataType): LengthAwarePaginator;

    /**
     * @param int $userId
     * @param array $brandsId
     * @return Collection
     */
    public function getUserBrands(int $userId, array $brandsId): Collection;

    /**
     * @param int $brandId
     * @param array $userIds
     * @return Collection
     */
    public function getUserBrandFromList(int $brandId, array $userIds): Collection;

    /**
     * @param int $id
     * @return int
     */
    public function delete(int $id): int;

    /**
     * @param int $brandId
     * @param float $sendex
     * @param string|null $emailResult
     * @return mixed
     */
    public function getByBrandIdLowerSendexLevel(int $brandId, float $sendex, ?string $emailResult);

    /**
     * @param int $brandId
     * @param UserBrand $userBrand
     * @param string $generation
     * @return mixed
     */
    public function updateGeneration(int $brandId, UserBrand $userBrand, string $generation);

    /**
     * @param int $brandID
     * @param int $userID
     * @return mixed
     */
    public function getUserSubscriptions(int $brandID, int $userID);

    /**
     * @param int $userID
     * @param int $brandID
     * @param array $subscriptions
     * @return mixed
     */
    public function updateSubscriptions($userId, $brandId, $subscriptions);

    /**
     * @param string $userEmail
     * @return mixed
     */
    public function getVisitedBrandsByEmail(string $userEmail);
}
