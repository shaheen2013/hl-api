<?php

namespace App\Repositories\Users;

use App\Repositories\Brand\BrandRepositoryInterface;
use App\Repositories\Visits\UserBrandRepository;
use App\Repositories\Visits\UserBrandRepositoryInterface;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var BrandRepositoryInterface
     */
    protected $brandRepository;

    /**
     * @var UserBrandRepositoryInterface
     */
    protected $userBrandRepository;

    public const LEGAL_AGE = 18;

    /**
     * UserRepository constructor.
     * @param User $user
     */
    public function __construct(User $user, BrandRepositoryInterface $brandRepository, UserBrandRepositoryInterface $userBrandRepository)
    {
        $this->user = $user;
        $this->brandRepository = $brandRepository;
        $this->userBrandRepository = $userBrandRepository;
    }

    /**
     * @param int $userId
     * @return mixed
     */
    public function getById(int $userId)
    {
        $tagName = ["user_" . $userId];
        $cacheName = "user_" . $userId;

        $user = Cache::tags($tagName)->get($cacheName);
        if (!$user) {
            $user = $this->user->findOrFail($userId);
            Cache::tags($tagName)->put($cacheName, $user, now()->addDays(10));
        }
        return $user;
    }

    /**
     * @param float $sendex
     * @return mixed
     */
    public function getByLowerSendexLevel(float $sendex)
    {
        return $this->user->select('id')->where('sendex', '<=', $sendex);
    }

    /**
     * @param string $email
     * @return mixed
     */
    public function getUserInfoByEmail(string $email)
    {
        return $this->user->where('email', $email)->firstOrFail();
    }

    public function attachUserToHotel(array $user, ?string $pms_id, ?int $customer, int $unsubscribed, int $commercial_profile, int $brand_id)
    {
        $brand = $this->brandRepository->get($brand_id);
        $hotel_id = $brand->hotel_id ?? null;

        if (!$hotel_id) {
            Log::warning("UserRepository", ["message" => "This brand has no hotel_id"]);
        }

        DB::transaction(function () use ($user, $pms_id, $customer, $brand, $hotel_id, $unsubscribed, $commercial_profile) {
            $user_id = $user['id'];
            $guid = $this->guidv4();
            $now = $this->getCurrentDateTime();
            $chain_id = $brand->chain_id ?? $brand->parent->chain_id ?? null;
            $user_hotel_id = $pms_id;

            // Generate subscription objects
            $subscriptions = [];
            $subscriptions[] = $this->generateSubscription('notifications', $unsubscribed == 0 ? 1 : 0, $now);
            $subscriptions[] = $this->generateSubscription('commercial_profile', $commercial_profile, $now);

            $subscriptions_json_escaped = addslashes(json_encode($subscriptions));

            $userData = $this->generateNewUserData($user);

            $user_hotels_query =
                "INSERT INTO user_hotels
                    (id_usuario, id_hotel, id_cadena, fecha, user_hotel_id, customer, guid, unsubscribed)
                VALUES ('$user_id', '$hotel_id', '$chain_id', '$now', '$user_hotel_id', '$customer', '$guid', $unsubscribed)
                ON DUPLICATE KEY UPDATE
                    id=LAST_INSERT_ID(id),
                    user_hotel_id = COALESCE(NULLIF('$user_hotel_id', ''), user_hotel_id),
                    customer = VALUES(customer),
                    guid = CASE WHEN guid = '' THEN VALUES(guid) ELSE guid END;";
            DB::insert($user_hotels_query);

            if ($brand->parent_id) {
                $this->updateUserHotelAttach($user, $brand->id, $unsubscribed, $commercial_profile);
            }
            $new_user_brand_query =
                "INSERT INTO new_user_brand
                    (brand_id, user_id, date, user_data, unsubscribed, commercial_profile, unsubscribed_at, subscriptions)
                VALUES ($brand->id, $user_id, '$now',  '" . $userData . "', $unsubscribed, $commercial_profile, " . ($unsubscribed ? "'$now'" : "NULL") . ",  '" . $subscriptions_json_escaped . "')
                ON DUPLICATE KEY UPDATE
                    id=LAST_INSERT_ID(id),
                subscriptions = JSON_MERGE_PRESERVE(
                    COALESCE(subscriptions, '[]'),
                    CAST('" . $subscriptions_json_escaped . "' AS JSON)
                );";

            DB::insert($new_user_brand_query);
        });
    }

    public function updateUserHotelAttach(array $user, int $brand_id, int $unsubscribed, int $commercial_profile)
    {
        $brand = $this->brandRepository->get($brand_id);
        $hotel_id = $brand->hotel_id ?? null;
        $now = $this->getCurrentDateTime();

        if (!$hotel_id) {
            Log::warning("UserRepository", ["message" => "This brand has no hotel_id"]);
        }

        DB::transaction(function () use ($user, $brand, $hotel_id, $unsubscribed, $commercial_profile, $now) {
            $user_id = $user['id'];
            $now = $this->getCurrentDateTime();

            $user_hotels_query = "UPDATE user_hotels SET unsubscribed='$unsubscribed' WHERE id_usuario = $user_id AND id_hotel = $hotel_id";
            DB::update($user_hotels_query);

            // Generate subscription objects
            $subscriptions = [];
            $subscriptions[] = $this->generateSubscription('notifications', $unsubscribed == 0 ? 1 : 0, $now);
            $subscriptions[] = $this->generateSubscription('commercial_profile', $commercial_profile, $now);

            $subscriptions_json_escaped = addslashes(json_encode($subscriptions));

            $userData = $this->generateNewUserData($user);
            
            $condition = "brand_id = $brand->id";

            if ($brand->parent) {
                $brandIds = $brand->parent->children->pluck('id')->implode(',');
                $condition = "brand_id IN ($brandIds)";
            }

            $new_user_brand_query = "
                UPDATE new_user_brand
                JOIN brands ON brands.id = new_user_brand.brand_id
                SET 
                    user_data = '" . $userData . "',
                    unsubscribed = '$unsubscribed',
                    commercial_profile= '$commercial_profile',
                    unsubscribed_at = " . ($unsubscribed ? "'$now'" : "NULL") . ",
                    subscriptions = JSON_MERGE_PRESERVE(
                        COALESCE(subscriptions, '[]'),
                        CAST('" . $subscriptions_json_escaped . "' AS JSON)
                    )
                WHERE user_id = $user_id AND $condition
            ";
            DB::update($new_user_brand_query);
        });
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createUser(array $data)
    {
        return $this->user->firstOrCreate(
            ['email' => $data['email']],
            $data
        );
    }

    /**
     * @param array $user
     * @param int $brand_id
     * @return mixed
     */
    public function create(array $user, int $brand_id)
    {
      
        $return_user = null;
        DB::transaction(function () use ($user, &$return_user, $brand_id) {
            $user_data = $this->mountUserDataToStore($user);
            $user_data['created'] = $this->getCurrentDateTime();
            $jsonData = json_encode($user_data);
            $createUserQuery = "INSERT INTO users (email, nombre, first_name, last_name, lang, sexo, fecha_nacimiento, location, created, user_card, sendex, email_result, pais, generation, data )
                VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)";
            DB::insert($createUserQuery, [
                $user_data['email'], $user_data['nombre'], $user_data['first_name'], $user_data['last_name'], $user_data['lang'],
                $user_data['sexo'], $user_data['fecha_nacimiento'], $user_data['location'], $user_data['created'], $user_data['user_card'],
                $user_data['sendex'], $user_data['email_result'], $user_data['pais'], $user_data['generation'], $jsonData
            ]);
            $return_user = $this->getById(DB::connection()->getPdo()->lastInsertId())->toArray();

            $this->attachUserToHotel($return_user, $user['pms_id'], $user['customer'], $user['unsubscribed'], $user['commercial_profile'], $brand_id);
            $userGuid = $this->guidv4();
            $user_guid_query = "INSERT INTO user_guid (id_usuario, guid) VALUES ('" . $return_user['id'] . "', '$userGuid')";
            DB::insert($user_guid_query);
        });
        return $return_user;
    }

    /**
     * @param array $data
     * @param int $brand_id
     * @return mixed
     */
    public function update(array $user, int $brand_id)
    {
        $return_user = null;
        DB::transaction(function () use ($user, &$return_user, $brand_id) {
            $age = $this->getAge($user['birthday']);
            if ($age >= self::LEGAL_AGE) {
                $user_data = $this->mountUserDataToStore($user);
                $jsonData = json_encode($user_data);
                //due to phone_number column doesn't exist we only update its value in the data column
                unset($user_data['phone_number']);
                $user_data['data'] = $jsonData;
                $return_user = tap($this->user->where('email', $user['email']))
                    ->update($user_data)
                    ->first()
                    ->toArray();
            } else {
                $return_user = tap($this->user->where('email', $user['email']))
                    ->update(["unsubscribed" => $user['unsubscribed']])
                    ->first()
                    ->toArray();
            }

            $user_brand = null;
            try {
                $user_brand = $this->userBrandRepository->get($brand_id, $return_user['id']);
            } catch (Exception $e) {
                Log::debug("UserRepository", ["message" => "User doesn't exist yet in this brand", "user" => $user, "brand_id" => $brand_id]);
            }

            if (!$user_brand) {
                $this->attachUserToHotel($return_user, $user['pms_id'], $user['customer'], $user['unsubscribed'], $user['commercial_profile'], $brand_id);
            } else {
                $this->updateUserHotelAttach($return_user, $brand_id, $user['unsubscribed'], $user['commercial_profile']);
            }
        });
        return $return_user;
    }

    private function mountUserDataToStore(array $user)
    {
        return [
            "email" => $user['email'],
            "nombre" => $user['first_name'] . ' ' . $user['last_name'],
            "first_name" => $user['first_name'],
            "last_name" => $user['last_name'],
            "lang" => $user['lang'],
            "sexo" => !empty($user['gender']) ? $user['gender'] : "",
            "fecha_nacimiento" => $user['birthday'],
            "location" => $user['locale'],
            "user_card" => $user['document_number'],
            "email_result" => $user['email_result'],
            "pais" => $user['country'],
            "sendex" => $user['sendex'],
            "unsubscribed" => $user['unsubscribed'],
            "generation" => $user['generation'],
            "phone_number" => $user['phone_number'] ?? ""
        ];
    }
    /**
     * @param int $userId
     * @param $generation
     * @return mixed
     */
    public function updateGeneration(int $userId, $generation)
    {
        return $this->user->where('id', $userId)->update(['generation' => $generation]);
    }

    /**
     * @param int $userId
     */
    public function delete(int $userId)
    {
        $this->user->find($userId)->delete();
    }

    //FX para crear un GUID
    private function guidv4()
    {
        $data = openssl_random_pseudo_bytes(16);
        assert(strlen($data) == 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    private function getCurrentDateTime()
    {
        return Carbon::now()->format('Y-m-d H:i:s');
    }

    private function getAge($birthdate)
    {
        return Carbon::parse($birthdate)->age;
    }

    public function generateSubscription($type, $subscribed, $date)
    {
        return [
            'type' => $type,
            'subscribed' => $subscribed,
            'date' => $date
        ];
    }

    public function generateNewUserData(array $user)
    {
        $first_name = Arr::get($user, 'first_name');
        $last_name = Arr::get($user, 'last_name');
        $name_value = trim($first_name . " " . $last_name);

        return addslashes(json_encode([
                "name" => $name_value,
                "first_name" => Arr::get($user, 'first_name'),
                "last_name" => Arr::get($user, 'last_name'),
                "email" => Arr::get($user, 'email'),
                "gender" => Arr::get($user, 'gender') === "" ? null : Arr::get($user, 'gender'),
                "birthdate" => Arr::get($user, 'birthday'),
                "phone_number" => Arr::get($user, 'phone_number'),
                "generation" => Arr::get($user, 'generation'),
                "lang" => Arr::get($user, 'lang'),
                "country" => Arr::get($user, 'country'),
            ]));
    }
}
