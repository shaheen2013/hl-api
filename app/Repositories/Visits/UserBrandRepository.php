<?php

namespace App\Repositories\Visits;

use App\Brand;
use App\Types\Users\ExternalUsersDataType;
use App\UserBrand;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserBrandRepository implements UserBrandRepositoryInterface
{
    /**
     * @var UserBrand
     */
    protected $userBrand;
    protected $cacheLifeInSeconds;
    protected $maxPaginate;
    protected $defaultPaginate;

    /**
     * UserBrandRepository constructor.
     * @param UserBrand $userBrand
     */
    public function __construct(UserBrand $userBrand)
    {
        $this->userBrand = $userBrand;
        // Configs
        $this->cacheLifeInSeconds = config('services.users.cacheLifeInSeconds');
        $this->maxPaginate = config('services.users.paginate.max');
        $this->defaultPaginate = config('services.users.paginate.default');
    }

    /**
     * @param $brandID
     * @param $userID
     * @return mixed
     */
    public function get($brandID, $userID)
    {
        return $this->userBrand->where([
            "brand_id" => $brandID,
            "user_id" => $userID
        ])->firstOrFail();
    }

    public function getUserSubscriptions($brandID, $userID)
    {
        return $this->userBrand->select('unsubscribed', 'commercial_profile')
            ->where([
                "brand_id" => $brandID,
                "user_id" => $userID
            ])
            ->first();
    }


    /**
     * @param $userID
     * @param $brandID
     * @param $unsubscribeNotifications
     * @param $unsubscribeCommercialProfile
     * @return mixed|void
     */

    public function unsubscribe($userId, $brandId, $unsubscribeNotifications = false, $unsubscribeCommercialProfile = false)
    {

        $updateData = [];
        if ($unsubscribeNotifications) {
            $updateData['unsubscribed'] = 1;
            $updateData['unsubscribed_at'] = now();
        }
        if ($unsubscribeCommercialProfile) {
            $updateData['commercial_profile'] = 0;
        }

        $brand = Brand::where('id', $brandId)->first();

        if ($brand->parent) {
            $brandIds = $brand->parent->children->pluck('id');

            $this->userBrand->whereIn(
                'brand_id',
                $brandIds
            )->where(
                'user_id',
                $userId
            )->update($updateData);
        } else {
            $this->userBrand->where([
                "brand_id" => $brandId,
                "user_id" => $userId
                ])->update($updateData);
        }

        Cache::tags(["brandUsers-{$brandId}"])->flush();
        Cache::tags(["clients_{$brandId}"])->flush();
    }


    /**
     * @param $userID
     * @param $brandID
     * @return mixed|void
     */
    public function updateSubscriptions($userId, $brandId, $subscriptions)
    {
        
        $brand = Brand::where('id', $brandId)->first();
       

        if ($brand->parent) {
            $brandIds = $brand->parent->children->pluck('id');
            $this->userBrand->whereIn(
                'brand_id',
                $brandIds
            )->where(
                'user_id',
                $userId
            )->update([
                'subscriptions' => json_encode($subscriptions),
            ]);
        } else {
            $this->userBrand->where([
                'brand_id' => $brandId,
                'user_id' => $userId
                ])->update([
                    'subscriptions' => json_encode($subscriptions),
                ]);
        }
    }

    /**
     * Method to mount query to get user data from a list of brandIds
     *
     * The query mounted is similar for this above:
     *    select
     *        `users`.`id` as `user_id`,
     *        `users`.`nombre` as `name`,
     *        `users`.`first_name` as `first_name`,
     *        `users`.`last_name` as `last_name`,
     *        `users`.`email` as `email`,
     *        `users`.`user_card` as `document_id`,
     *        `users`.`fecha_nacimiento` as `birthday`,
     *        `users`.`sexo` as `gender`,
     *        `users`.`location` as `location`,
     *        `users`.`lang` as `lang`,
     *        `users`.`pais` as `country`,
     *        `users`.`email_result` as `email_result`,
     *        `users`.`sendex` as `sendex`,
     *        `users`.`unsubscribed` as `unsubscribed`,
     *        `new_user_brand`.`date` as `created_at`,
     *        `user_facebook`.`id_facebook` as `facebook_id`,
     *        `user_facebook`.`facebook_img` as `facebookImg`,
     *        `user_facebook`.`locationID` as `location_id`,
     *        `user_facebook`.`locationName` as `locationName`,
     *        `brands`.`id` as `brand_id`,
     *        `hoteles`.`hotelName` as `brand_name`,
     *        `users_visits`.`last_login` as `last_visit`
     *        `users_visits`.`num_visits` as `num_visits`
     *    from `new_user_brand`
     *    inner join `new_visit` on `new_visit`.`user_brand_id` = `new_user_brand`.`id`
     *    inner join `brands` on `brands`.`id` = `new_user_brand`.`brand_id`
     *    inner join `hoteles` on `hoteles`.`id` = `brands`.`hotel_id`
     *    inner join `users_visits` on `users_visits`.`hotel_id` = `hoteles`.`id` and `users_visits`.`user_id` = `new_user_brand`.`user_id`
     *    inner join `users` on `users`.`id` = `new_user_brand`.`user_id` and `users`.`lang` = ?
     *    left join `user_facebook` on `user_facebook`.`id_usuario` = `users`.`id`
     *    where `new_user_brand`.`brand_id` in (?)
     *    group by `users`.`id`
     *    order by `new_user_brand`.`date` desc
     *
     * @param array $brandIds
     * @param string|null $lang
     *
     * @return Builder
     */
    private function mountQueryToGetUserBrandDataByBrandIds(array $brandIds, ?string $lang = null): Builder
    {
        return $this->userBrand
            ->select([
                'new_user_brand.id as id',
                'users.id as user_id',
                'users.nombre as name',
                'users.first_name as first_name',
                'users.last_name as last_name',
                'users.email as email',
                'users.user_card as document_id',
                'users.fecha_nacimiento as birthday',
                'users.sexo as gender',
                'users.location as location',
                'users.lang as lang',
                'users.pais as country',
                'users.email_result as email_result',
                'users.sendex as sendex',
                'users.data->phone_number as phone_number',
                // DB::raw("JSON_UNQUOTE(JSON_EXTRACT(users.data, '$.phone_number')) as phone_number"),
                'new_user_brand.unsubscribed as unsubscribed', // ??? new_user_brand or we should use from user_hotel ?????
                'new_user_brand.date as created_at',
                'user_facebook.id_facebook as facebook_id',
                'user_facebook.facebook_img as facebook_img',
                'user_facebook.locationID as location_id',
                'user_facebook.locationName as location_name',
                'brands.id as brand_id',
                'hoteles.hotelName as hotel_name',
                'users_visits.last_login as last_visit',
                'users_visits.num_visits as num_visits',
                'new_visit.reservation as reservation'
            ])
            ->join('new_visit', function ($join) use ($brandIds) {
                $join->on('new_visit.user_brand_id', '=', 'new_user_brand.id');
            })
            ->join('brands', 'brands.id', '=', 'new_user_brand.brand_id')
            ->join('hoteles', 'hoteles.id', '=', 'brands.hotel_id')
            ->join('users_visits', function ($join) {
                $join
                    ->on('users_visits.hotel_id', '=', 'hoteles.id')
                    ->on('users_visits.user_id', '=', 'new_user_brand.user_id');
            })
            ->join('users', function ($join) use ($lang) {
                $join->on('users.id', '=', 'new_user_brand.user_id');
                // If passed a lang
                if ($lang) {
                    $join->where('users.lang', '=', $lang);
                }
            })
            ->leftJoin('user_facebook', 'user_facebook.id_usuario', '=', 'users.id')
            ->whereIn('new_user_brand.brand_id', $brandIds)
            ->groupBy('users.id')
            ->with('connections');
    }

    /**
     *  Method to return the query for brand users using joins to improve the performance
     *
     * @param array $brandIds
     * @param string|null $lang
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param bool|null $unsubscribed
     *
     * @return Builder
     */
    private function getAnswersQueryByBrandListAndFilters(array $brandIds, ?string $lang, ?string $dateFrom = null, ?string $dateTo = null, ?bool $unsubscribed): Builder
    {
        // Mount query with select and joins necessary
        $query = $this->mountQueryToGetUserBrandDataByBrandIds($brandIds, $lang);

        // Filter dateFrom
        if ($dateFrom) {
            $query->where('new_user_brand.date', '>=', $dateFrom);
        }
        // Filter dateTo
        if ($dateTo) {
            $query->where('new_user_brand.date', '<=', $dateTo);
        }

        // Check if we have the unsubscribed query param, if exists, then do this logic
        if (isset($unsubscribed)) {
            $query->where('new_user_brand.unsubscribed', $unsubscribed);
        }
        // OrderBy to return firts the new ones
        $query->orderBy('new_user_brand.date', 'DESC');

        return $query;
    }

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
    public function getUserByBrandIdAndUserId(int $brandId, int $userId)
    {
        // Define cache name with filters
        $cacheName = "brandUser-brandId:{$brandId}-userId:{$userId}";
        // Define a list of tags for brandId and userId fo be flushed on changes on brand or user
        $cacheTag = [
            "brand-{$brandId}",
            "user-{$userId}",
        ];
        if (Cache::tags($cacheTag)->has($cacheName)) {
            Log::debug("UserBrandRepository", [
                'message' => 'get brandUser using cache',
                "cacheName" => $cacheName,
                "cacheTag" => $cacheTag
            ]);
            $user = Cache::tags($cacheTag)->get($cacheName);
        } else {
            Log::debug("UserBrandRepository", [
                'message' => 'get brandUser performing query',
                "cacheName" => $cacheName,
                "cacheTag" => $cacheTag
            ]);
            // Get query using joins
            $query = $query = $this->mountQueryToGetUserBrandDataByBrandIds([$brandId]);
            // find user
            $user = $query
                ->where("users.id", $userId)
                ->firstOrFail();
            // Add to cache
            Cache::tags($cacheTag)->put($cacheName, $user, $this->cacheLifeInSeconds);
        }

        return $user;
    }

    /**
     * Get brand users applyng some filters passed on a array $filterList
     * Some of this filters are:
     *  - language: filter users by user lang
     *  - dateFrom: filter users since dateFrom
     *  - dateTo: filter users until dateTo
     *  - unsubscribed: filter users by unsubscribed
     *
     *  The paginate filters:
     *  - page: the page wanted
     *  - pageItems: number of items per page
     *
     * @param int $parentBrandId - the brand id from chain or hotel in case of independent hotel.
     * @param array $brandIds - the brand ids to be search users.
     * @param App\Types\Users\ExternalUsersDataType $externalUsersDataType
     *
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginatedAnswerWithFilters(int $parentBrandId, array $brandIds, ExternalUsersDataType $externalUsersDataType): LengthAwarePaginator
    {
        // ---- Get all input filters
        $lang = $externalUsersDataType->language;
        $dateFrom = $externalUsersDataType->dateFrom;
        $dateTo = $externalUsersDataType->dateTo;
        $pageItems = $externalUsersDataType->pageItems ?? $this->defaultPaginate;
        $page = $externalUsersDataType->page;
        $unsubscribed = $externalUsersDataType->unsubscribed;

        // Get paginate pageItems and limit it to $this->maxPaginate
        $pageItems = ($pageItems > $this->maxPaginate) ? $this->maxPaginate : $pageItems;

        // Define cache name with filters
        $cacheName = "brandUsers-parentBrandId:$parentBrandId-brandIds:" . implode(',', $brandIds) . "-lang:$lang-date_from:$dateFrom-date_to:$dateTo-page:$page-page_items:$pageItems:$unsubscribed-unsubscribed";
        // Define a tag foreach brandId in the query, because when became a new user, we flush the cache that has this tag
        $cacheTag = collect($brandIds)
            ->sort()
            ->map(function ($brandId) {
                return "brandUsers-$brandId";
            })
            ->toArray();

        // Check cache
        if (Cache::tags($cacheTag)->has($cacheName)) {
            Log::debug("UserBrandRepository", ['message' => 'using cache', "cacheName" => $cacheName, "cacheTag" => $cacheTag]);
            $paginate = Cache::tags($cacheTag)->get($cacheName);
        } else {
            Log::debug("UserBrandRepository", ['message' => 'performing query', "cacheName" => $cacheName, "cacheTag" => $cacheTag]);
            // Get query using joins to improve performance
            $query = $this->getAnswersQueryByBrandListAndFilters($brandIds, $lang, $dateFrom, $dateTo, $unsubscribed);
            // Paginate
            $paginate = $query->paginate($pageItems);
            // attatch filter params to url
            $paginate->appends(array_filter($externalUsersDataType->getAttributesForUrl()));
            // Add to cache
            Cache::tags($cacheTag)->put($cacheName, $paginate, $this->cacheLifeInSeconds);
        }

        return $paginate;
    }

    /**
     * @param int $userId
     * @param array $brandsId
     * @return Collection
     */
    public function getUserBrands(int $userId, array $brandsId): Collection
    {
        return $this->userBrand
            ->where('user_id', $userId)
            ->whereIn('brand_id', $brandsId)
            ->get();
    }

    public function getUserBrandFromList(int $brandId, array $userIds): Collection
    {
        return $this->userBrand->where("brand_id", $brandId)
            ->whereIn('user_id', $userIds)
            ->get();
    }

    /**
     * @param int $id
     * @return int
     */
    public function delete(int $id): int
    {
        return $this->userBrand
            ->destroy($id);
    }

    /**
     * @param int $brandId
     * @param float $sendex
     * @param string|null $emailResult
     * @return mixed
     */
    public function getByBrandIdLowerSendexLevel(int $brandId, float $sendex, ?string $emailResult)
    {
        $query = $this->userBrand
            ->select([
                'new_user_brand.*'
            ])
            ->join('users', 'users.id', '=', 'new_user_brand.user_id')
            ->where('new_user_brand.brand_id', $brandId)
            ->where('users.sendex', '<=', $sendex);

        // Apply email result to query if passed
        if ($emailResult) {
            $query->where('users.email_result', '=', $emailResult);
        }
        return $query;
    }

    public function getVisitedBrandsByEmail($userEmail)
    {
        return $this->userBrand
            ->join('users', 'users.id', '=', 'new_user_brand.user_id')
            ->join('brands', 'brands.id', "=", "new_user_brand.brand_id")
            ->select('new_user_brand.user_id as user_id', 'new_user_brand.brand_id as brand_id', 'new_user_brand.date as date', 'new_user_brand.unsubscribed as unsubscribed', 'users.email as email', 'users.nombre as name', 'brands.hotel_id as hotel_id')
            ->where('users.email', '=', $userEmail)
            ->get();
    }

    public function updateGeneration(int $brandId, UserBrand $userBrand, string $generation)
    {

        $brand = $userBrand->brand;

        $condition = "brand_id = $brand->id";

        if ($brand->parent) {
            $brandIds = $brand->parent->children->pluck('id')->implode(',');
            $condition = "brand_id IN ($brandIds)";
        }

        return $this->userBrand
        ->where('user_id', $userBrand->user_id)
        ->whereRaw($condition)
        ->update(['user_data' => DB::raw("JSON_SET(user_data, '$.generation', '$generation')")]);
    }
}
