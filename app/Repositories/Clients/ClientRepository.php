<?php

namespace App\Repositories\Clients;

use App\Visit;
use App\UserBrand;
use App\Brand;
use App\Types\Clients\SearchClientsDataType;
use App\Types\Clients\ClientsExportDataType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use PDO;
use Symfony\Component\HttpFoundation\File\Exception\CannotWriteFileException;

class ClientRepository implements ClientRepositoryInterface
{
    /**
     * @var Visit
     */
    protected $visit;
    protected $userBrand;
    const CHAIN_BRAND_TYPE = 1;


    /**
     * ClientRepository constructor.
     * @param UserBrand $user
     */
    public function __construct(Visit $visit, UserBrand $userBrand)
    {
        $this->visit = $visit;
        $this->userBrand = $userBrand;
    }

    /**
     * Create the basic clients query
     * @param Brand $brand
     * @param SearchClientsDataType|ClientsExportDataType $searchData
    */
    private function buildClientsQuery(Brand $brand, $searchData, $query, string $tableSearch)
    {
        if ($brand->brand_type_id == self::CHAIN_BRAND_TYPE) {
            $brands = $brand->children()
                ->join('hoteles', 'brands.hotel_id', '=', 'hoteles.id')
                ->where(['activated' => 1])
                ->pluck('brands.id');

            $query = $query->whereIn($tableSearch . ".brand_id", $brands);
        }

        if ($searchData->hosted) {
            $query = $query->where('new_visit.check_out', '>', Carbon::now());
        }

        if ($searchData->subscribed) {
            $query = $query->where('new_user_brand.unsubscribed', '=', 0);
        }

        if ($searchData->from) {
            $query = $query->where('new_user_brand.date', '>', $searchData->from);
        }

        if ($searchData->to) {
            $query = $query->where('new_user_brand.date', '<', $searchData->to);
        }

        if ($searchData->searchText) {
            if ($searchData->searchBy === 'email') {
                $query = $query->where('new_user_brand.user_email', '=', $searchData->searchText);
            } else if ($searchData->searchBy === 'name') {
                $query = $query->whereRaw("(MATCH (`new_user_brand`.`user_name`) AGAINST ('\"+" . $searchData->searchText . "*\"' IN BOOLEAN MODE))");
            } else if ($searchData->searchBy === 'res_id') {
                $query = $query->where('new_visit.res_id', '=', $searchData->searchText);
            } elseif ($searchData->searchBy === 'pms_id') {
                $query = $query->where('new_visit.pms_id', '=', $searchData->searchText);
            } elseif ($searchData->searchBy === 'access_code') {
                $query = $query->where('new_visit.access_code', '=', $searchData->searchText);
            } else {
                $query = $query->whereRaw("(MATCH (`new_user_brand`.`user_name`) AGAINST ('\"+" . $searchData->searchText . "*\"' IN BOOLEAN MODE))");
            }
        }

        return $query;
    }

    /**
     * @param Brand $brand
     * @param SearchClientsDataType $searchData
     * @return mixed
     */
    public function get(Brand $brand, SearchClientsDataType $searchData)
    {
        $cacheName = 'clients_' . $brand->id . '_hosted_' . $searchData->hosted . '_subscribed_' . $searchData->subscribed . '_from_' . $searchData->from . '_searchBy_' . $searchData->searchBy . '_to_' . $searchData->to . '_searchText_' . $searchData->searchText . '_sortField_' . $searchData->sortField . '_sortOrder_' . $searchData->sortOrder . '_page_' . $searchData->page . '_perPage_' . $searchData->perPage;
        $cacheTags = ['clients_' . $brand->id];
        $cacheTime = $searchData->hosted ? Carbon::now()->secondsUntilEndOfDay() : Carbon::now()->addDays(7);

        $clients = Cache::tags($cacheTags)->get($cacheName);
        if (!$clients) {
            $fields = [
                'new_user_brand.brand_id as brand_id',
                'new_user_brand.date',
                'new_user_brand.unsubscribed as unsub',
                'new_user_brand.user_name as client_name',
                'new_user_brand.user_email as client_email',
                'new_user_brand.user_phone_number as phone_number',
                'new_user_brand.user_id as user_id',
                'reservation->check_in as check_in',
                'reservation->check_out as check_out',
                'reservation->res_channel as channel',
                'reservation->connections as connections',
                'reservation->res_agency as agency',
                'reservation->res_date as res_date',
            ];
            
            $clients =  $this->visit->join('new_user_brand', 'new_visit.user_brand_id', '=', 'new_user_brand.id');
            $tableSearch = $searchData->hosted ? "new_visit" : "new_user_brand";
            $clients = $this->buildClientsQuery($brand, $searchData, $clients, $tableSearch);

            if ($searchData->sortField == 'hoteles.hotelName') {
                $searchData->sortField = 'new_user_brand.user_id';
            }
            
            if ($brand->brand_type_id !== self::CHAIN_BRAND_TYPE) {
                if ($searchData->hosted) {
                    $clients = $clients->where(['new_visit.brand_id' => $brand->id]);
                } else {
                    $clients = $clients->where(['new_user_brand.brand_id' => $brand->id]);
                }
            }
            try {
                $clients = $clients
                    ->groupBy('new_user_brand.user_id')
                    ->orderBy($searchData->sortField, $searchData->sortOrder)
                    ->select(...$fields)
                    ->limit($searchData->perPage)
                    ->offset(($searchData->page - 1) * $searchData->perPage);

                if ($brand->brand_type_id === self::CHAIN_BRAND_TYPE) {
                    $clients = DB::table(DB::raw("({$clients->toSql()}) as mainQuery"))
                        ->mergeBindings($clients->getQuery())
                        ->join('brands', 'mainQuery.brand_id', '=', 'brands.id')
                        ->join('hoteles', 'brands.hotel_id', '=', 'hoteles.id')
                        ->select('mainQuery.*', 'hoteles.hotelName');
                }

                $clients = $clients->get();
            } catch (\Exception $e) {
                Log::error("Exception on pagination clients", [$e]);
            }

            Cache::tags($cacheTags)->put($cacheName, $clients, $cacheTime);
        }

        return $clients;
    }

    public function count(Brand $brand, SearchClientsDataType $searchData)
    {
        $cacheName = 'clients_count_' . $brand->id . '_hosted_' . $searchData->hosted . '_subscribed_' . $searchData->subscribed . '_from_' . $searchData->from . '_to_' . $searchData->to . '_searchText_' . $searchData->searchText;
        $cacheTags = ['clients_' . $brand->id];
        $cacheTime = $searchData->hosted ? Carbon::now()->secondsUntilEndOfDay() : Carbon::now()->addDays(7);

        $clients = Cache::tags($cacheTags)->get($cacheName);

        if (!$clients) {
            $clients =  $this->visit;
            $tableSearch = "new_visit";

            if ($searchData->subscribed || $searchData->from || $searchData->to || $searchData->searchText) {
                $clients = $clients->join('new_user_brand', 'new_visit.user_brand_id', '=', 'new_user_brand.id');
                $tableSearch = "new_user_brand";
            }

            $clients = $this->buildClientsQuery($brand, $searchData, $clients, $tableSearch);
            
            if ($brand->brand_type_id !== self::CHAIN_BRAND_TYPE) {
                $clients = $clients->where([$tableSearch . '.brand_id' => $brand->id]);
            }

            try {
                $clients = $clients->distinct()->count($tableSearch . '.user_id');
            } catch (\Exception $e) {
                Log::error("Exception on pagination clients", [$e]);
            }

            Cache::tags($cacheTags)->put($cacheName, $clients, $cacheTime);
        }

        return $clients;
    }

    /**
     * @param Brand $brand
     * @param SearchClientsDataType $searchData
     * @return mixed
     */
    public function getCsv(Brand $brand, ClientsExportDataType $searchData, array $headers)
    {
        Log::info("CLIENT REPORT INFO: Starting getCsv repository", ["brand" => $brand->id, "memory_usage" => memory_get_peak_usage()]);

        $mainQueryFields = [
            'new_user_brand.brand_id as brand_id', 'new_user_brand.date', 'new_user_brand.unsubscribed as unsub', 'new_user_brand.user_name as client_name', DB::raw("NULLIF(JSON_UNQUOTE(JSON_EXTRACT(user_data, '$.first_name')), 'null') AS first_name"),
            DB::raw("NULLIF(JSON_UNQUOTE(JSON_EXTRACT(user_data, '$.last_name')), 'null') AS last_name"),'new_user_brand.user_email as client_email','new_user_brand.user_birthdate as client_birthday', 'new_user_brand.user_id as user_id',
            'new_user_brand.user_country as client_country', 'new_user_brand.user_gender as client_gender', 'new_user_brand.user_lang as lang',
            'reservation->pms_id as pms_id','reservation->check_in as check_in', 'reservation->check_out as check_out', 'reservation->res_channel as channel', 'reservation->res_agency as agency', 'reservation->res_date as res_date',
            DB::raw('CASE 
            WHEN `reservation` IS NOT NULL 
                 AND json_length(json_extract(`reservation`, \'$."connections"\')) > 0 
            THEN json_unquote(json_extract(`reservation`, concat(\'$."connections"[\', json_length(json_extract(`reservation`, \'$."connections"\')) - 1, \']\')))
            ELSE NULL 
            END as `last_connection`'),
            DB::raw('CASE user_satisfaction.done WHEN 1 THEN user_satisfaction.puntuacion ELSE NULL END as `satisfaction_answer`'),
            'user_satisfaction.comentario as satisfaction_comment',
           
        ];
        $genericQueryFields = [
            'mainQuery.*', 'hotelName', 'user_visits_hotel.num_visits as hotel_visits', 'user_visits_chain.num_visits as chain_visits'
        ];

        if ($searchData->phone_active) {
            $mainQueryFields[] = 'new_user_brand.user_phone_number as phone_number';
        }

        if ($searchData->commercial_profile) {
            $mainQueryFields[] = 'new_user_brand.commercial_profile as commercial_profile';
        }

        // We force index on new_user_brand join for better query performance. This has to be monitorized because if the data distribution changes
        // it can not be efficient
        if ($brand->brand_type_id == self::CHAIN_BRAND_TYPE) {
            $clientsMainQuery =  $this->visit->join(DB::raw('new_user_brand FORCE INDEX (new_user_brand_user_id_brand_id_unique)'), 'new_visit.user_brand_id', '=', 'new_user_brand.id');
        } else {
            $clientsMainQuery = $this->visit->join('new_user_brand', 'new_visit.user_brand_id', '=', 'new_user_brand.id');
        }

        $clientsMainQuery = $this->buildClientsQuery($brand, $searchData, $clientsMainQuery, "new_user_brand");

        if ($brand->brand_type_id == self::CHAIN_BRAND_TYPE) {
            $timezone = $brand->children[0]->hotel->timeZone->time_zone;
        } else {
            $timezone = $brand->hotel->timeZone->time_zone;
            $clientsMainQuery = $clientsMainQuery->where(['new_user_brand.brand_id' => $brand->id]);
        }

        $clientsMainQuery = $clientsMainQuery
            ->leftJoin('user_survey', function ($join) {
                $join
                    ->on('user_survey.brand_id', '=', 'new_user_brand.brand_id')
                    ->on('user_survey.user_id', '=', 'new_user_brand.user_id');
            })
            ->leftJoin('user_satisfaction', function ($join) {
                $join->on('user_survey.user_satisfaction_id', '=', 'user_satisfaction.id');
            });

        Log::info("CLIENT REPORT INFO: Query prepared", ["brand" => $brand->id, "memory_usage" => memory_get_peak_usage()]);

        $filename = '/export_clients_csv-' . str_replace('.', '-', microtime(true)) . '.tmp';
        $localFile = sys_get_temp_dir() . $filename;
        $csv = fopen($localFile, 'w+');
        fprintf($csv, chr(0xEF) . chr(0xBB) . chr(0xBF));

        Log::info("CLIENT REPORT INFO: Opening csv file", ["brand" => $brand->id, "memory_usage" => memory_get_peak_usage()]);

        if (!$csv) {
            Log::error('Cannot write file', ['file' => $localFile]);
            throw new CannotWriteFileException();
        }

        fputcsv($csv, $headers, ";");

        // We created a simple subquery to pull the clients through chunks to avoid memory problems. If the query is limited in a normal way
        // it becomes very slow when the offset is very large, so we set the offset always to 0 and limit and add a condition with the
        // last user that has been included. The chunk is then processed as a cursor to avoid memory problems again.
        // We have actually simulated the chunkById function included in laravel, merging it with a subquery to improve performance.
        $lastUserId = null;
        $chunkSize = 50000;
        while (true) {
            [$lastUserId, $index] = $this->writeClientsOnCsv($clientsMainQuery, $mainQueryFields, $chunkSize, $lastUserId, $genericQueryFields, $timezone, $searchData, $csv);
            
            // If the index is less than the chunkSize it means that there are no more clients and we must exit the loop.
            if ($index < $chunkSize) {
                break;
            };
        }

        Log::info("CLIENT REPORT INFO: Finishing csv file", ["brand" => $brand->id]);

        fclose($csv);

        Log::info("CLIENT REPORT INFO: Csv file closed", ["brand" => $brand->id]);

        return $localFile;
    }

    public function getClientDetail(Brand $brand, int $userId)
    {
        $cacheName = 'clients_' . $brand->id . '_user_' . $userId;
        $cacheTags = ['clients_' . $brand->id];
        $cacheTime = Carbon::now()->addDays(30);

        $clients = Cache::tags($cacheTags)->get($cacheName);

        if (!$clients) {
            $parentId = data_get($brand, 'parent_id');

            $clients =  $this->userBrand
                ->leftJoin('user_facebook', 'user_facebook.id_usuario', '=', 'new_user_brand.user_id')
                ->join('brands', 'new_user_brand.brand_id', '=', 'brands.id')
                ->join('hoteles', 'brands.hotel_id', '=', 'hoteles.id');
            
            if ($parentId) {
                $clients = $clients
                    ->where([
                        'parent_id' => $parentId,
                        "user_id"   => $userId,
                        'hoteles.activated' => 1
                    ]);
            } else {
                $clients = $clients->where([
                    "brand_id"  => $brand->id,
                    "user_id"   => $userId
                ]);
            }

            $clients = $clients->with(['visits' => function ($visitQuery) {
                $visitQuery->select(
                    'new_visit.id as id',
                    'user_brand_id',
                    'new_user_brand.brand_id',
                    'reservation',
                    'reservation->pms_id as pms_id',
                    'reservation->res_id as reservation_id',
                    'check_in',
                    'check_out',
                    'reservation->res_channel as channel',
                    'reservation->res_agency as agency',
                    'reservation->telephone as telephone',
                    'user_survey_question_answer.answer as answer',
                    'user_survey_question_answer.comment as comment',
                    'user_survey_question_answer.created_at as created_at',
                    'is_client'
                )
                ->join('new_user_brand', 'new_user_brand.id', 'new_visit.user_brand_id')
                ->leftJoin('user_survey', function ($join) {
                    $join->on('user_survey.brand_id', '=', 'new_user_brand.brand_id')
                        ->on('user_survey.user_id', '=', 'new_user_brand.user_id')
                        ->on('user_survey.created_at', '>=', DB::raw('DATE_SUB(new_visit.check_in, INTERVAL 1 HOUR)'))
                        ->on('user_survey.created_at', '<=', 'new_visit.check_out');
                })
                ->leftJoin('user_survey_question_answer', function ($join) {
                    $join->on('user_survey_question_answer.user_survey_id', '=', 'user_survey.id')
                        ->on('user_survey_question_answer.id', '=', DB::raw("(select min(id) from user_survey_question_answer WHERE user_survey_question_answer.user_survey_id = user_survey.id)"));
                });

                $visitQuery->with(['connections' => function ($connectionQuery) {
                    $connectionQuery
                        ->select(
                            'new_connection.id as id',
                            'new_connection.visit_id as visit_id',
                            'access_code',
                            'mac_address',
                            'name',
                            'new_connection.created_at',
                            'device_family',
                            'operating_system',
                            'operating_system_version'
                        )
                        ->leftJoin('new_device', 'new_device.id', 'new_connection.device_id')
                        ->leftJoin('new_access_type', 'new_access_type.id', 'new_connection.access_type_id');
                }]);
            }]);
            $clients = $clients->select(
                'new_user_brand.id as id',
                'new_user_brand.user_id as user_id',
                'new_user_brand.user_name as user_name',
                'new_user_brand.user_email as user_email',
                'new_user_brand.user_phone_number as phone_number',
                'new_user_brand.user_birthdate as fecha_nacimiento',
                'new_user_brand.user_gender as sexo',
                'new_user_brand.user_country as country',
                'locationName',
                'new_user_brand.unsubscribed as unsubscribed',
                'id_facebook',
                'facebook_img',
                'link',
                'amigos',
                'brands.id as brand_id',
                'hotelName'
            )->get();

            Cache::tags($cacheTags)->put($cacheName, $clients, $cacheTime);
        }

        return $clients;
    }

    private function parseDate($date, $timezone)
    {
        if (!$date || $date == 'null') {
            return "N/A";
        }

        $formats = [
            'Y-m-d H:i:s',
            'Y-m-d\TH:i:s.uP',
            'Y'
        ];

        foreach ($formats as $fmt) {
            try {
                return Carbon::createFromFormat($fmt, $date, 'UTC')->setTimezone($timezone)->toDateTimeString();
            } catch (\Exception $e) {
                // Continue to next format
            }
        }

        // If all formats fail, return original date
        return $date;
    }

    // This has to be in a separate function to improve memory usage
    private function writeClientsOnCsv($clientsMainQuery, $mainQueryFields, $chunkSize, $lastUserId, $genericQueryFields, $timezone, $searchData, $csv)
    {
        Log::info("CLIENT REPORT INFO: Start new iteration", ["lastUserId" => $lastUserId, "memory_usage" => memory_get_peak_usage()]);

        $clients = (clone $clientsMainQuery)
            ->groupBy('new_user_brand.user_id')
            ->orderBy('new_user_brand.user_id', 'desc')
            ->select(...$mainQueryFields)
            ->limit($chunkSize)
            ->offset(0);

        
        if ($lastUserId) {
            $clients = $clients->where('new_user_brand.user_id', '<', $lastUserId);
        }

        $clients = DB::table(DB::raw("({$clients->toSql()}) as mainQuery"))
            ->mergeBindings($clients->getQuery())
            ->join('brands', 'mainQuery.brand_id', '=', 'brands.id')
            ->join('hoteles', 'brands.hotel_id', '=', 'hoteles.id')
            ->leftJoin('cadena_hotel', 'cadena_hotel.id_hotel', 'hoteles.id')
            ->leftJoin('users_visits as user_visits_hotel', function ($join) {
                $join
                    ->on('user_visits_hotel.hotel_id', '=', 'hoteles.id')
                    ->on('user_visits_hotel.user_id', '=', 'mainQuery.user_id');
            })
            ->leftJoin('users_visits as user_visits_chain', function ($join) {
                $join
                    ->on('user_visits_chain.user_id', '=', 'mainQuery.user_id')
                    ->on('user_visits_chain.chain_id', '=', 'cadena_hotel.id_cadena');
            })
            ->select(...$genericQueryFields)
            ->orderBy('mainQuery.user_id', 'desc');

        $index = 0;

        foreach ($clients->cursor() as $client) {
            $lastConnection = json_decode($client->last_connection, true);

            $row = [
                data_get($client, 'client_name'),
                data_get($client, 'first_name'),
                data_get($client, 'last_name'),
                data_get($client, 'client_email'),
                data_get($client, 'hotelName'),
                data_get($client, 'client_birthday'),
                data_get($client, 'client_country'),
                data_get($client, 'lang', 'Unknown') ? data_get($client, 'lang') : 'Unknown',
                data_get($client, 'client_gender'),
                $this->parseDate(data_get($lastConnection, 'date'), $timezone),
                $this->parseDate(data_get($client, 'date'), $timezone),
                $this->parseDate(data_get($client, 'res_date'), $timezone),
                $this->parseDate(data_get($client, 'check_in'), $timezone),
                $this->parseDate(data_get($client, 'check_out'), $timezone),
                data_get($client, 'channel') && data_get($client, 'channel') != "null" ? data_get($client, 'channel') : "N/A",
                data_get($client, 'agency') && data_get($client, 'agency') != "null" ? data_get($client, 'agency') : "N/A",
                data_get($client, 'pms_id'),
                data_get($client, 'hotel_visits', 1),
                data_get($client, 'chain_visits', 1),
                data_get($client, 'unsub') ? 'No' : 'Yes',
                data_get($lastConnection, 'access_code'),
                data_get($client, 'satisfaction_answer'),
                data_get($client, 'satisfaction_comment'),
            ];
            if ($searchData->phone_active) {
                $row[] = data_get($client, 'phone_number') ?? 'N/A';
            }

            if ($searchData->commercial_profile) {
                $row[] = data_get($client, 'commercial_profile') ? 'Yes' : 'No';
            }

            fputcsv($csv, $row, ";");

            $lastUserId = data_get($client, 'user_id');
            $index++;
        }

        return [$lastUserId, $index];
    }
}
