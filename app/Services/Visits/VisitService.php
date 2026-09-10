<?php

namespace App\Services\Visits;

use App\Connection;
use App\UserBrand;
use App\Exceptions\HLException;
use App\Repositories\Visits\AccessTypeRepositoryInterface;
use App\Repositories\Visits\DeviceRepositoryInterface;
use App\Visit;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class VisitService
{
    /**
     * @var DeviceRepository
     */
    protected $deviceRepository;


    /**
     * @var AccessTypeRepository
     */
    protected $accessTypeRepository;

    /**
     * VisitService constructor.
     * @param DeviceRepositoryInterface $deviceRepository
     * @param AccessTypeRepositoryInterface $accessTypeRepository
     */
    public function __construct(
        DeviceRepositoryInterface $deviceRepository,
        AccessTypeRepositoryInterface $accessTypeRepository
    ) {
        $this->deviceRepository = $deviceRepository;
        $this->accessTypeRepository = $accessTypeRepository;
    }

    /**
     * @param $userBrandID
     * @param $timeStamp
     * @return mixed
     */
    public function getVisit($userBrandID, $timeStamp)
    {

        Log::debug('getVisit', [$userBrandID, $timeStamp]);
        try {
            return Visit::where(
                [
                    ["user_brand_id", $userBrandID],
                    ["check_out", '>=', $timeStamp],
                    ["check_in", '<=', $timeStamp]
                ]
            )
                ->orderBy('check_in', 'desc')
                ->first();
        } catch (\Exception $e) {
            Log::error('getVisit', [$e->getMessage()]);
        }
    }

    /**
     * Store visit in DB
     *
     * @param int $userBrandId
     * @param stdClass $Stay
     * @param array $reservation
     *
     * @return object
     */
    public function storeVisit(UserBrand $userBrand, \stdClass $stay, array $reservation = null): object
    {
        Log::debug('VisitService', [
            "message" => "Creating visit",
            "userBrandId" => $userBrand->id,
            'stay' => $stay,
            'reservation' => $reservation
        ]);

        $check_in = $reservation ? new Carbon(Arr::get($reservation, 'check_in')) : $stay->check_in;
        $check_out = $reservation ? new Carbon(Arr::get($reservation, 'check_out')) : $stay->check_out;

        try {
            return Visit::create([
                "user_brand_id" => $userBrand->id,
                "check_in"      => $check_in->toDateTimeString(),
                "check_out"     => $check_out->toDateTimeString(),
                "is_client"     => $stay->is_client,
                "reservation"   => $reservation ? json_encode($reservation) : null,
                "brand_id"      => $userBrand->brand_id,
                "user_id"       => $userBrand->user_id
            ]);
        } catch (QueryException $e) {
            $visit = $this->getVisit($userBrand->id, Carbon::now());

            if (!$visit) {
                throw new HLException(
                    'VisitService',
                    [
                        'message' => 'Trying to update visit, but visit not found',
                        "userBrandId" => $userBrand->id,
                        'error' => $e->getMessage(),
                        'reservation' => json_encode($reservation)
                    ]
                );
            }

            return $this->updateVisit($visit, $stay, $reservation);
        }
    }

    /**
     * Update visit by updating checkout and is_client values
     *
     * @param Visit $visit
     * @param stdClass $stay
     * @param array $reservation
     *
     * @return boolean
     */
    public function updateVisit(Visit $visit, \stdClass $stay, array $reservation = null): bool
    {

        if (!$visit->reservation) {
            if (!$reservation) {
                $visit->check_out = $stay->check_out;
                $visit->is_client = $stay->is_client;

                return $visit->save();
            }

            Log::info(
                'VisitService',
                [
                    'message' => 'Updating visit',
                    "visit" => $visit,
                    'stay' => $stay,
                    'reservation' => $reservation
                ]
            );

            $check_in = new Carbon(Arr::get($reservation, 'check_in'));
            $check_out = new Carbon(Arr::get($reservation, 'check_out'));

            $visit->check_in = $check_in->toDateTimeString();
            $visit->check_out = $check_out->toDateTimeString();
            $visit->is_client = $stay->is_client;
            $visit->reservation = json_encode($reservation);

            return $visit->save();
        } else {
            Log::info(
                'VisitService',
                [
                    'message' => "Visit with reservation data found, no need to update",
                    "visit" => $visit,
                    "stay" => $stay,
                    "reservation" => $reservation
                ]
            );
            return true;
        }
    }

    public function storeConnection($userID, $brandID, $visitID, $stay, $userAgent, $connection)
    {
        $accessType = $this->accessTypeRepository->get(Arr::get($connection, 'accessType', "form"));

        $device = $this->deviceRepository->firstOrCreate(
            Arr::get($userAgent, 'macAddress'),
            Arr::get($userAgent, 'deviceFamily'),
            Arr::get($userAgent, 'deviceBrand'),
            Arr::get($userAgent, 'deviceModel')
        );

        Connection::create([
            "brand_id"                  => $brandID,
            "visit_id"                  => $visitID,
            "device_id"                 => data_get($device, 'id'),
            "access_type_id"            => data_get($accessType, 'id', 1),
            "access_code"               => Arr::get($connection, 'accessCode', ''),
            "is_client"                 => data_get($stay, 'is_client', 0),
            "headers"                   => Arr::get($userAgent, 'headers'),
            "browser"                   => Arr::get($userAgent, 'browser'),
            "browser_version"           => Arr::get($userAgent, 'browserVersion'),
            "browser_lang"              => data_get($userAgent, 'browserLang'),
            "operating_system"          => Arr::get($userAgent, 'operatingSystem'),
            "operating_system_version"  => Arr::get($userAgent, 'operatingSystemVersion'),
            "device_brand"              => Arr::get($userAgent, 'deviceBrand')
        ]);


        try {
            $connectionData = [
                "date"          => Carbon::now()->format('Y-m-d H:i:s'),
                "access_code"   => Arr::get($connection, 'accessCode', ''),
                "source"        => data_get($accessType, 'name', 'Form'),
                "device" => [
                    "mac_address"       => Arr::get($userAgent, 'macAddress'),
                    "family"            => Arr::get($userAgent, 'deviceFamily'),
                    "brand"             => Arr::get($userAgent, 'deviceBrand'),
                    "model"             => Arr::get($userAgent, 'deviceModel'),
                    "operating_system"  => Arr::get($userAgent, 'operatingSystem') . ' ' . Arr::get($userAgent, 'operatingSystemVersion')
                ]
            ];

            // Insert connection info into reservation data JSON. First, if reservation is null, it initializes it as an empty object.
            // Then if no array of connections exists, it creates it and finally adds a new position to the array with connection data.
            Visit::where('id', $visitID)->update([
                'reservation' => DB::raw(
                    "JSON_SET(
                        COALESCE(reservation, '{}'), '$.connections', COALESCE(
                            JSON_ARRAY_APPEND(reservation->'$.connections', '$', CAST('" . addslashes(json_encode($connectionData)) . "' AS JSON)), 
                            JSON_ARRAY(CAST('" . addslashes(json_encode($connectionData)) . "' AS JSON))
                        )
                    )"
                )
            ]);
        } catch (QueryException $e) {
            Log::error("Can't save connection on visit table", ["error" => $e]);
        }

        return;
    }
}
