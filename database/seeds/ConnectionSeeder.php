<?php

use Illuminate\Database\Seeder;
use App\ConnectionHistory;
use App\Connection;
use App\Visit;
use App\UserHotel;
use App\UserBrand;
use App\Device;
use App\AccessType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ConnectionSeeder extends Seeder
{
    const MAX_TIMES_LOGIN = 50;
    const NO_MAC_PREFIX = 'NO_MAC_';
    private $accessType = null;
    private $visit = null;
    private $device = null;
    private $userBrand = null;
    private $userHotel = null;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->setAccessType();

        $this->command->getOutput()->progressStart(ConnectionHistory::count());

        ConnectionHistory::chunk(1000, function($connection)
        {
            foreach ($connection as $connectionHistory) {
                if (!$connectionHistory->id_user || !$connectionHistory->id_hotel) {
                    continue;
                }

                $times_login = ($connectionHistory->times_login > self::MAX_TIMES_LOGIN) ? self::MAX_TIMES_LOGIN : $connectionHistory->times_login;
                $this->setUserHotel($connectionHistory);
                $this->setUserBrand($connectionHistory);
                $this->setVisit($connectionHistory);
                $this->setDevice($connectionHistory);
                $this->insertConnection($times_login, $connectionHistory);

                $this->command->getOutput()->progressAdvance();
            }
        });

        $this->command->getOutput()->progressFinish();


    }

    private function insertConnection($connections = 1, ConnectionHistory $connectionHistory)
    {
        if (!$connections || !$this->userBrand || !$this->userHotel || !$this->visit) {
            return;
        }

        $newConnections = [];
        for ($i = 0; $i < $connections; $i ++) {

            try {
                $newConnections[] = [
                    'brand_id'                 => $this->userHotel->brand->id,
                    'visit_id'                 => $this->visit->id,
                    'device_id'                => $this->device->id,
                    'access_type_id'           => $this->userHotel->user->userFacebook ? $this->accessType['Facebook']['id'] : $this->accessType['Form']['id'],
                    'access_code'              => $connectionHistory->id_room,
                    'is_client'                => $this->userHotel->customer ?? false,
                    'headers'                  => $connectionHistory->headers,
                    'browser'                  => $connectionHistory->browser,
                    'browser_version'          => $connectionHistory->browser_version,
                    'browser_lang'             => $connectionHistory->browser_lang,
                    'operating_system'         => $connectionHistory->operating_system,
                    'operating_system_version' => $connectionHistory->operating_system_version,
                    'created_at'               => $connectionHistory->first_login,
                    'updated_at'               => $connectionHistory->last_login
                ];

            } catch (\Exception $exception) {
                Log::error('PREPARE CONNECTION: ' . $exception->getMessage());
            }
        }

        if (!empty($newConnections)) {
            try {
                Connection::insert($newConnections);
            } catch (\Exception $exception) {
                Log::error('INSERT CONNECTION: ' . $exception->getMessage());
            }
        }

    }

    private function setUserHotel(ConnectionHistory $connectionHistory)
    {
        if ($this->userHotel && $this->userHotel->id_hotel == $connectionHistory->id_hotel && $this->userHotel->id_usuario == $connectionHistory->id_user) {
            return;
        }

        try {
            $this->userHotel = UserHotel::where('id_hotel', $connectionHistory->id_hotel)->where('id_usuario', $connectionHistory->id_user)->first();

            if ($this->userHotel) {
                $this->userHotel->load('brand');
                $this->userHotel->load('user');
            }

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    private function setUserBrand(ConnectionHistory $connectionHistory)
    {
        if (!$this->userHotel || ($this->userBrand && $this->userBrand->brand_id == $this->userHotel->brand->id && $this->userBrand->user_id == $connectionHistory->id_user)) {
            return;
        }

        try {
            $this->userBrand = UserBrand::where('brand_id', $this->userHotel->brand->id)->where('user_id', $connectionHistory->id_user)->first();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    private function setVisit(ConnectionHistory $connectionHistory)
    {
        if ($this->visit && $this->userBrand && $this->visit->user_brand_id == $this->userBrand->id) {
            return;
        }

        try {
            $connectionVisit = ConnectionHistory::where(['id_user' => $connectionHistory->id_user, 'id_hotel' => $connectionHistory->id_hotel])
                ->whereBetween('last_login', [Carbon::parse($connectionHistory->first_login)->subDays(7), Carbon::parse($connectionHistory->first_login)->subHours(1)])    
                ->first();
           
            if (!$connectionVisit) {
                $this->visit = Visit::firstOrCreate(
                    [
                        'user_brand_id' => $this->userBrand->id,
                        'check_in'  => $connectionHistory->first_login,
                        'check_out' => $connectionHistory->last_login,
                        'is_client' => $this->userHotel->customer ?? false
                    ]
                );
            } else {
                $this->visit = Visit::where(['user_brand_id' => $this->userBrand->id])->orderBy('check_in', 'desc')->first();
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    private function setDevice(ConnectionHistory $connectionHistory)
    {
        $brand = $this->userHotel->brand->id ?? microtime(true);

        $fakeMac = self::NO_MAC_PREFIX . md5(
                $brand .
                $connectionHistory->id_user .
                $connectionHistory->device_family .
                $connectionHistory->device_brand .
                $connectionHistory->device_model
            );

        $mac = empty(trim($connectionHistory->mac_address)) ? $fakeMac : $connectionHistory->mac_address;

        if ($this->device &&
            $this->device->mac_address == $mac &&
            $this->device->device_family == $connectionHistory->device_family &&
            $this->device->device_model == $connectionHistory->device_model) {
            return;
        }

        try {
            $this->device = Device::firstOrCreate(
                [
                    'mac_address' => $mac
                ],
                [
                    'device_family' => $connectionHistory->device_family,
                    'device_brand'  => $connectionHistory->device_brand,
                    'device_model'  => $connectionHistory->device_model
                ]
            );

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    private function setAccessType()
    {
        $this->accessType = AccessType::all()->keyBy('name')->toArray();
    }

}
