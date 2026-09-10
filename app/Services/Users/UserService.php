<?php

namespace App\Services\Users;

use App\Events\UserUnsubscribedEvent;
use App\Repositories\Users\UserRepositoryInterface;
use App\Repositories\Visits\UserBrandRepositoryInterface;
use App\Repositories\Visits\UserHotelRepositoryInterface;
use App\Repositories\Gdpr\GdprRepositoryInterface;
use App\Repositories\Brand\BrandRepositoryInterface;
use App\Exceptions\BrandIdsListNotSameChainException;
use App\Types\Users\ExternalUsersDataType;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Arr;
use App\Events\UserCreatedEvent;
use App\Events\UserUpdatedEvent;
use Carbon\Carbon;

class UserService
{
    protected $userRepository;
    protected $userBrandRepository;
    protected $userHotelRepository;
    protected $brandRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     * @param UserBrandRepositoryInterface $userBrandRepository
     * @param UserHotelRepositoryInterface $userHotelRepository
     * @throws \Exception
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        UserBrandRepositoryInterface $userBrandRepository,
        UserHotelRepositoryInterface $userHotelRepository,
        BrandRepositoryInterface $brandRepository
    ) {
        $this->userRepository = $userRepository;
        $this->userBrandRepository = $userBrandRepository;
        $this->userHotelRepository = $userHotelRepository;
        $this->brandRepository = $brandRepository;
    }

    /**
     * @param $userID
     * @return \Illuminate\Cache\CacheManager|mixed
     * @throws \Exception
     */
    public function getUser($userID)
    {
        return $this->userRepository->getById($userID);
    }

    /**
     * @param float $sendex
     * @return mixed
     */
    public function getByLowerSendexLevel(float $sendex)
    {
        return $this->userRepository->getByLowerSendexLevel($sendex);
    }

    /**
     * @param int $brandId
     * @param float $sendex
     * @param string|null $emailResult
     * @return mixed
     */
    public function getByBrandIdAndLowerSendexLevel(int $brandId, float $sendex, ?string $emailResult = null)
    {
        return $this->userBrandRepository->getByBrandIdLowerSendexLevel($brandId, $sendex, $emailResult);
    }

    /**
     * @param array $request
     * @return mixed
     */
    public function createUser(array $request)
    {
        $request['pais'] = $request['pais'] ?? get_country_name($request['location']);
        $request['created'] = $request['created'] ?? Carbon::now()->format('Y-m-d H:i:s');
        $request['generation'] = $request['fecha_nacimiento'] ? $this->getGeneration($request['fecha_nacimiento']) : null;

        return $this->userRepository->createUser($request);
    }

    /**
     * @param array $data
     * @param int $brand_id
     */
    public function updateOrCreate(array $user, int $brand_id)
    {
        $old_user = null;
        // Check whether user already exists or not
        try {
            $old_user = $this->getUserInfoByEmail($user['email'])->toArray();
        } catch (Exception $e) {
            Log::debug("UserService", ["message" => 'User doesnt exist yet']);
        }

        $user['generation'] = $this->getGeneration($user['birthday']);

        // Extra data for event payload
        $extra_data = [
            "pms_id"            => $user['pms_id'],
            "customer"          => $user['customer'],
            "origin"            => Arr::get($user, 'origin'),
            "facebook_id"       => $user['facebook_id'] ?? null,
            "facebook_img"      => $user['facebook_img'] ?? null,
            "facebook_friends"  => $user['facebook_friends'] ?? null
        ];

        // Create user if it doesn't exist in database
        if (!$old_user) {
            try {
                $return_user = $this->userRepository->create($user, $brand_id);
                event(new UserCreatedEvent($this->mountUserEventPayload($return_user, $extra_data, $brand_id)));

                return [
                    "user" => $return_user,
                    "isNew" => true
                ];
            } catch (Exception $e) {
                Log::error("UserService", ["message" => "Failed to create user", "error" => $e->getMessage(), "file" => $e->getFile(), "line" => $e->getLine()]);
                return;
            }
        }

        // Update user if it already exists
        // Compare old user and new user
        $userSimilarity = $this->calculateSimilarityBetweenUsers($old_user, $user);
        if ($userSimilarity < 80) {
            Log::warning("UserService", [
                "message" => "User similarity not matched",
                "brand_id" => $brand_id,
                "email" => $user['email'],
                "percent" => $userSimilarity,
                "old_user" => $old_user,
                "new_user" => $user
            ]);
        }
        try {
            $return_user = $this->userRepository->update($user, $brand_id);
            event(new UserUpdatedEvent($this->mountUserEventPayload($return_user, $extra_data, $brand_id)));

            return [
                "user" => $return_user,
                "isNew" => false
            ];
        } catch (Exception $e) {
            Log::error("UserService", ["message" => "Failed to update user", "error" => $e->getMessage(), "file" => $e->getFile(), "line" => $e->getLine()]);
            return;
        }
    }

    private function mountUserEventPayload(array $user, array $extra_data, int $brand_id)
    {
        $payload = [
            "brand" => ["id" => $brand_id],
            "user" => [
                "id" => $user['id'],
                "first_name" => $user['first_name'],
                "last_name" => $user['last_name'],
                "email" => $user['email'],
                "gender" => $user['gender'],
                "lang" => $user['lang'],
                "locale" => $user['location'],
                "country" => $user['country'],
                "document_number" => $user['user_card'],
                "sendex" => (float) $user['sendex'],
                "email_result" => $user['email_result'],
                "birthday" => $user['birthday'],
                "unsubscribed" => $user['unsubscribed'],
                "pms_id" => $extra_data['pms_id'],
                "customer" => $extra_data['customer'],
                "origin" => $extra_data['origin']
            ]
        ];

        if ($extra_data['facebook_id']) {
            $payload['user']['facebook_id'] = $extra_data['facebook_id'];
            $payload['user']['facebook_img'] = $extra_data['facebook_img'];
            $payload['user']['facebook_friends'] = $extra_data['facebook_friends'];
        }

        return $payload;
    }

    /**
     * Calculate similarity between old user and new user
     *
     * @param array $old_user
     * @param array $new_user
     *
     * @return float
     */
    public function calculateSimilarityBetweenUsers(array $old_user, array $user): float
    {
        $old_user = [
            $old_user['first_name'],
            $old_user['last_name'],
            $old_user['email'],
            $old_user['birthday'],
            $old_user['gender']
        ];
        $old_user_str = implode('', $old_user);

        $new_user = [
            $user['first_name'],
            $user['last_name'],
            $user['email'],
            $user['birthday'],
            $user['gender']
        ];
        $new_user_str = implode('', $new_user);

        // Calculate similarity between users.
        similar_text(trim(strtoupper($old_user_str)), trim(strtoupper($new_user_str)), $percent);
        return $percent;
    }

    /**
     * @param $userID
     * @param $generation
     */
    public function updateGeneration($userID, $generation)
    {
        $this->userRepository->updateGeneration($userID, $generation);
    }

    /**
     * @param int $userId
     */
    public function deleteUser(int $userId)
    {
        $this->userRepository->delete($userId);
    }

    /**
    * @param $userId
    * @param $brandId
    */

    public function getBrandUserSubscriptions(int $brandId, int $userId)
    {
        $result = $this->userBrandRepository->getUserSubscriptions($brandId, $userId);

        if ($result) {
            return [
                'notifications_subscribed' => $result->unsubscribed ? 0 : 1,
                'commercial_profile_subscribed' => $result->commercial_profile ? 1 : 0
            ];
        }

        return null;
    }

    /**
     * @param $userId
     * @param $brandId
     * @param $data
     */
    public function unsubscribe($userId, $brandId, $data)
    {
        try {
            $userBrand = $this->userBrandRepository->get($brandId, $userId);
            $unsubscribeNotifications = $data['unsubscribe_notifications'] ;
            $unsubscribeCommercialProfile = $data['unsubscribe_commercial_profile'];

            $subscriptions = json_decode($userBrand->subscriptions, true);

            $shouldUnsubscribeFromNotifications = $unsubscribeNotifications && $userBrand->unsubscribed == 0;
            $shouldUnsubscribeFromCommercialProfile = $unsubscribeCommercialProfile && $userBrand->commercial_profile == 1;

            if ($shouldUnsubscribeFromNotifications || $shouldUnsubscribeFromCommercialProfile) {
                Log::debug('UserService', ['message' => 'Performing user unsubscription', "userBrand" => $userBrand, 'userId' => $userId]);

                $userHotel = $this->userHotelRepository->getByUserIDBrandID($userId, $brandId);

                // Perform unsubscriptions
                if ($userHotel &&  $shouldUnsubscribeFromNotifications) {
                    $this->userHotelRepository->unsubscribe($userId, $userHotel->id_hotel);
                }

                // Add the appropriate unsubscribed entry to the subscription array
                if ($shouldUnsubscribeFromNotifications) {
                    $subscriptions[] = $this->userRepository->generateSubscription('notifications', 0, now());
                }

                if ($shouldUnsubscribeFromCommercialProfile) {
                    $subscriptions[] = $this->userRepository->generateSubscription('commercial_profile', 0, now());
                }

                $this->userBrandRepository->updateSubscriptions($userId, $brandId, $subscriptions);

                $this->userBrandRepository->unsubscribe($userId, $brandId, $shouldUnsubscribeFromNotifications, $shouldUnsubscribeFromCommercialProfile);

                Log::debug('UserService', ['message' => 'Performing user unsubscribe event', "brandId" => $brandId, 'userId' => $userId]);

                //Emit event to EventBridge
                event(new UserUnsubscribedEvent(['brand' => ['id' => $brandId], 'user' => ['id' => $userId]]));
                Log::info('UserService', ['message' => 'User unsubscribe event sent', "brandId" => $brandId, '$userId' => $userId]);
            } else {
                Log::info('UserService', ['message' => 'User already unsubscribed', "brandId" => $brandId, 'userId' => $userId]);
            }
        } catch (Exception $e) {
            Log::error('UserService', ['message' => 'Error unsubscribing user', 'error' => $e->getMessage(), "brandId" => $brandId, 'userId' => $userId]);
        }
    }


    // TODO: once eventbridge user unsubscribed works fine, remove this and all sns methods related to user unsbscribption.
    /**
     * @param $userID
     * @param $brand
     */
    public function unsubscribeViaSns($userID, $brand)
    {
        $subscriptions[] = $this->userRepository->generateSubscription('notifications', 0, now());
        $this->userBrandRepository->updateSubscriptions($userID, $brand->id, $subscriptions);

        // Unsubscribe the user from notifications
        $this->userBrandRepository->unsubscribe($userID, $brand->id, true, false);

        $this->userHotelRepository->unsubscribe($userID, $brand->hotel_id);
    }
    /**
     *
     * @param int $brandId
     * @param int $userId
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function getBrandUser(int $brandId, int $userId)
    {
        return $this->userBrandRepository->getUserByBrandIdAndUserId($brandId, $userId);
    }



    /**
     * @param $email
     * @return \Illuminate\Cache\CacheManager|mixed
     * @throws \Exception
     */
    public function getUserInfoByEmail($email)
    {
        $user = $this->userRepository->getUserInfoByEmail($email);

        return $user;
    }

    /**
     * @param $userID
     * @param $brandIDs
     * @return Collection
     */
    public function getUserBrand($brandID, $userID)
    {
        return $this->userBrandRepository->get($brandID, $userID);
    }

    /**
     * Method to get brand users filtering by brand_id and applying some filter like language, dates, ...
     *
     * @param int brandId
     * @param App\Types\Users\ExternalUsersDataType externalUsersDataType
     *
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getBrandUsers(int $brandId, ExternalUsersDataType $externalUsersDataType): LengthAwarePaginator
    {
        // Get brandIds to perform a check if is a chain or a hotel
        $brand = $this->brandRepository->get($brandId);
        // In case of hotel get the parent
        $parent = $brand->parent ?? $brand;
        // Do not look for brands on inputs
        $brandIds = [$brandId];

        // Check if is a chain
        if ($brand->chain_id) {
            // In case of chain, we need to verify if all hotels on the list of brands belongs to this chain
            $parent = $brand;
            // Get list of brand from inputs
            $brandIds = $externalUsersDataType->brands;
            // Get list of childrens to validate
            $brandChildren = $parent->children->pluck('id')->values();

            if (empty($brandIds)) {
                // In case not passed a list of brands for a chain, search for all hotels for this chain
                $brandIds = $brandChildren->toArray();
            } elseif (!empty(array_diff($brandIds, $brandChildren->toArray()))) {
                // Check if that is a brand that no belongs to this chain
                throw new BrandIdsListNotSameChainException();
            }
        }

        return $this->userBrandRepository
            ->getPaginatedAnswerWithFilters($parent->id, $brandIds, $externalUsersDataType);
    }

    /**
     * @param int $id
     * @return int
     */
    public function delete(int $id): int
    {
        return $this->userRepository
            ->delete($id);
    }

    /**
     * @param $brandID
     * @param $userID
     * @return mixed
     */
    public function getUserBrands($brandIDs, $userID)
    {
        return $this->userBrandRepository->getUserBrands($userID, $brandIDs);
    }

    public function getVisitedBrandsByEmail($userEmail)
    {
        return $this->userBrandRepository->getVisitedBrandsByEmail($userEmail);
    }


    public function getRelatedUsersInBrand(int $brandId, array $userIds)
    {
        $userBrands = $this->userBrandRepository->getUserBrandFromList($brandId, $userIds);

        [$suscribedUsers, $unsubscribedUsers] = $userBrands->partition(function ($value) {
            return $value->unsubscribed === 0;
        });

        $usersToUpdate = $suscribedUsers->pluck('user_id')->toArray();
        $unsubscribedUserIds = $unsubscribedUsers->pluck('user_id')->toArray();

        $usersNotInBrand = Arr::where($userIds, function ($value) use ($usersToUpdate, $unsubscribedUserIds) {
            return !in_array($value, $usersToUpdate) && !in_array($value, $unsubscribedUserIds);
        });

        return [
            "usersToUpdate"     => $usersToUpdate,
            "unsubscribedUsers" => $unsubscribedUserIds,
            "usersNotInBrand"   => $usersNotInBrand
        ];
    }

    /**
     * @param $birthdate
     * @return mixed
     */
    public function getGeneration($birthdate)
    {
        $generations = [
            '1927-01-01' => 'mature',
            '1946-01-01' => 'baby boomer',
            '1965-01-01' => 'generation x',
            '1981-01-01' => 'millenial',
            '2001-01-01' => 'generation z'
        ];

        return Arr::last($generations, function ($generation, $date) use ($birthdate) {
            $time = strtotime($date);
            $birthday = strtotime($birthdate);

            return $birthday >= $time;
        });
    }
}
