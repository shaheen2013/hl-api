<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Hotel;
use App\BrandProtocol;
use App\Http\Resources\Brands\BrandInfoResource;
use App\Http\Resources\Brands\BrandInfoResourcePaginate;
use App\Services\BrandService;
use App\Types\Brands\PutBrandInfoDataType;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Exceptions\InvalidRequestException;
use App\Types\Brands\PutBrandArchiveDataType;

class BrandController extends Controller
{
    const SUGGEST_LIMIT = 5;
    private $childs;
    private $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function index()
    {
        return Brand::all();
    }

    /**
     * @SWG\Get(
     *     path="/api/brands/{id}",
     *     description="Shows all brand info related to the given brand_id
    (<strong style='color:red'>int</strong>  brand_id: : identifier that will be used to relate all requested info with a concreted HL brand client data)
    The answer will follow the next structure:
    {
    <strong style='color:blue'>Id</strong>: <strong style='color:red'>integer</strong> requested brand id,
    <strong style='color:blue'>email</strong>: <strong style='color:red'>String</strong> email that was used to register in Hotelinking,
    <strong style='color:blue'>sending_email</strong>: <strong style='color:red'>String</strong> email that will be used as sender for corporate emails
    <strong style='color:blue'>name</strong>: <strong style='color:red'>String</strong> brand name
    <strong style='color:blue'>estrellas</strong> (Opcional): <strong style='color:red'>Integer</strong> number of stars associated to the brand
    <strong style='color:blue'>city</strong> (Optional): <strong style='color:red'>String</strong> Brand location using google place notations
    <strong style='color:blue'>street</strong> (Optional): <strong style='color:red'>String</strong> Brand address as it was supplied in the registration in Hotelinking
    <strong style='color:blue'>place_name</strong> (Optional): <strong style='color:red'>String</strong> Brand city using google place notations
    <strong style='color:blue'>place_country</strong> (Optional): <strong style='color:red'>String</strong> Brand country using google place notations
    <strong style='color:blue'>place_adm_area</strong> (Optional): <strong style='color:red'>String</strong> Brand state using google place notations
    }",
     *     tags={"Brands"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         type="integer",
     *         description="Brand id:
    id para obtener los datos del cliente de HL ",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="success",
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing Data"
     *     )
     * )
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->brandService->getBrand($id)->brandInfo;
    }

    /**
     * @SWG\Get(
     *     path="/api/brands/{id}/childs/{type}",
     *     description="Get brands by parent brand",
     *     tags={"Brands"},
     *      @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         type="integer",
     *         description="Brand id",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="type",
     *         in="path",
     *         type="string",
     *         description="Child brand type like hotel...",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         allowEmptyValue=true,
     *         name="users",
     *         in="query",
     *         type="string",
     *         description="Appends registered users",
     *         required=false,
     *     ),
     *     @SWG\Parameter(
     *         allowEmptyValue=true,
     *         name="score",
     *         in="query",
     *         type="string",
     *         description="Appends average satisfaction score",
     *         required=false,
     *     ),
     *     @SWG\Parameter(
     *         allowEmptyValue=true,
     *         name="registers",
     *         in="query",
     *         type="string",
     *         description="Last register",
     *         required=false,
     *     ),
     *     @SWG\Parameter(
     *         name="staff_id",
     *         in="query",
     *         type="integer",
     *         description="Filter by staff",
     *         required=false,
     *     ),
     *     @SWG\Parameter(
     *         name="suggest",
     *         in="query",
     *         type="string",
     *         description="String to search the name, country and place fields",
     *         required=false,
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Childs by brand",
     *     )
     * )
     *
     * Display the specified resource.
     *
     * @param  int $id
     * @param  string $type
     * @return \Illuminate\Http\Response
     */

    public function getBrandChilds(Request $request, $id, $type)
    {
        $brand = Brand::findOrFail($id);
        $brandInfo = $brand->getBrandInfo();

        switch ($type) {
            case 'hotel':
                $childs = $this->getHotelsFromBrand($brand, $request);
                break;

            default:
                return response('Child type not found', 404);
        }

        $brandInfo['childs'] = count($childs);
        $brandInfo['child_data'] = $childs;

        return $brandInfo;
    }

    /**
     * @SWG\Get(
     *     path="/api/brands/{id}/protocol/",
     *     description="Get protocols by brand",
     *     tags={"Brands"},
     *      @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         type="integer",
     *         description="Brand id",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="service",
     *         in="path",
     *         type="string",
     *         description="Service name to filter protocol",
     *         required=false,
     *     ),
     *  @SWG\Response(
     *         response=200,
     *         description="Childs by brand",
     *     )
     * )
     *
     * Display the specified resource.
     *
     * @param  int $id
     * @param  string $service
     * @return \Illuminate\Http\Response
     */
    public function getProtocolsByBrand($id, $service = null)
    {
        $protocols = BrandProtocol::where('brand_id', $id);

        if ($service) {
            $protocols = $protocols->where('service', $service);

            return $protocols->where('service', $service)
                ->first();
        }

        return $protocols->get();
    }

    private function getHotelsFromBrand($brand, Request $request)
    {
        $hotels = [];

        $this->childs = DB::table('hoteles')->select(
            'brands.id as brand_id',
            'hoteles.id as hotel_id',
            'hoteles.hotelName',
            'hoteles.logo',
            'hoteles.lang',
            'hoteles.place_name',
            'hoteles.country',
            'hoteles.lat',
            'hoteles.lng'
        )->join(
            'brands',
            'brands.hotel_id',
            '=',
            'hoteles.id'
        );

        $this->appendStaffFilter($request);
        $this->appendHotelScore($request);
        $this->appendLastRegisterHotel($request);
        $this->appendFullTextSearch($request);

        $userHotels = $this->appendUsersHotel($request, $brand);

        $this->childs = $this->childs->where([
            'brands.parent_id'  => $brand->id,
            'hoteles.activated' => 1
        ])->orderBy(
            'hoteles.hotelName',
            'asc'
        )->get();

        foreach ($this->childs as $childHotel) {
            $users = $userHotels
                ? Arr::first($userHotels, function ($val) use ($childHotel) {
                    return data_get($val, 'brand_id') == $childHotel->brand_id;
                })
                : null;

            $hotels[] = [
                'id'                 => $childHotel->hotel_id,
                'name'               => htmlspecialchars($childHotel->hotelName, ENT_NOQUOTES, 'UTF-8'),
                'place'              => htmlspecialchars($childHotel->place_name, ENT_NOQUOTES, 'UTF-8'),
                'logo'               => $childHotel->logo,
                'lang'               => $childHotel->lang,
                'country'            => $childHotel->country,
                'lat'                => $childHotel->lat,
                'lng'                => $childHotel->lng,
                'users'              => data_get($users, 'count') ?? 0,
                'score'              => data_get($childHotel, 'score'),
                'last_register'      => data_get($childHotel, 'last_date'),
                'last_register_days' => data_get($childHotel, 'days'),
                'last_registers'     => data_get($childHotel, 'registers')
            ];
        }

        return $hotels;
    }

    private function appendHotelScore(Request $request)
    {
        if ($request->has('score')) {
            $this->childs->addSelect(DB::raw("
                (
                    SELECT
                        ROUND(AVG(puntuacion), 2)
                    FROM 
                        user_satisfaction
                    WHERE
                        done = 1 AND
                        id_hotel = hoteles.id
                ) AS score
            "));
        }
    }

    private function appendUsersHotel(Request $request, $brand)
    {
        if ($request->has('users')) {
            $activatedChildsIds = $brand->children->filter(function ($val) {
                return $val->brandInfo->activated;
            })->pluck('id')->toArray();

            return DB::select("
                SELECT new_user_brand.brand_id, COUNT(DISTINCT new_user_brand.user_id) as count
                    FROM
                        new_visit INNER JOIN `new_user_brand` on `new_visit`.`user_brand_id` = `new_user_brand`.`id`
                    WHERE 
                        new_user_brand.brand_id IN (" . implode(",", $activatedChildsIds) . ") 
                        AND date > '" . Carbon::now()->subDay() . "'
                    group by new_user_brand.brand_id;");
        }
    }

    private function appendLastRegisterHotel(Request $request)
    {
        if ($request->has('registers')) {
            $this->childs->addSelect(
                'history.registers',
                'history.last_date',
                'history.days'
            )->leftJoin(
                DB::raw("
                (
                    SELECT
                        id_hotel,
                        registers,
                        last_date,
                        days
                    FROM
                    (
                        SELECT
                            id_hotel,
                            COUNT(1) AS registers,
                            DATE(first_login) AS last_date,
                            DATEDIFF(CURDATE(), DATE(first_login)) AS days
                        FROM
                            connection_history
                        GROUP BY
                            id_hotel,
                            last_date
                        ORDER BY
                            last_date
                        DESC
                    ) AS ld
                    GROUP BY
                        id_hotel
                ) AS history
            "),
                function ($join) {
                    $join->on(
                        'history.id_hotel',
                        '=',
                        'hoteles.id'
                    );
                }
            );
        }
    }

    private function appendFullTextSearch(Request $request)
    {
        if ($request->get('suggest')) {
            $this->childs
                ->fullTextSearch($request->get('suggest'))
                ->limit(self::SUGGEST_LIMIT);
        }
    }

    private function appendStaffFilter(Request $request)
    {
        if ((int)$request->get('staff_id')) {
            $this->childs
                ->join(
                    'hotel_staff_hotels',
                    'hotel_staff_hotels.hotel_id',
                    '=',
                    'hoteles.id'
                )->where(
                    'hotel_staff_hotels.hotel_staff_id',
                    (int)$request->get('staff_id')
                );
        }
    }

    /**
     * @SWG\Post(
     *     path="/api/brands/{id}/languages/{name}/url",
     *     description="Inserts into table the brand_url_language",
     *     tags={"Brands"},
     *      @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="BrandUrlLanguage brand_id",
     *         required=true,
     *     ),
     *      @SWG\Parameter(
     *         name="name",
     *         in="path",
     *         type="integer",
     *         description="BrandUrlLanguage language_name",
     *         required=true,
     *     ),
     *      @SWG\Parameter(
     *         name="url",
     *         in="path",
     *         type="string",
     *         description="brand_url_language Website URL for the brand_id and language_id specified.",
     *         required=true,
     *     ),
     *  @SWG\Response(
     *         response=200,
     *     )
     * )
     *
     * Inserts the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function setBrandUrlLanguage(Request $request)
    {
        if ($request->id && $request->name && $request->url) {
            return response(
                $this->brandService->setBrandUrlLanguage($request->id, $request->name, $request->url),
                200
            );
        }

        return response(400);
    }

    /**
     * @SWG\Get(
     *     path="/api/brandUrlLanguage",
     *     description="Gets all data from table brandUrlLanguage",
     *     tags={"Brands"},
     *  @SWG\Response(
     *         response=200,
     *         description="Childs by brand",
     *     )
     * )
     *
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllBrandUrlLanguages()
    {
        return response(
            $this->brandService->getAllBrandUrlLanguages(),
            200
        );
    }

    /**
     * @SWG\Get(
     *     path="/api/brands/url/brand/{brand_id}",
     *     description="Gets brandUrlLanguage by the brand_id.",
     *     tags={"Brands"},
     *      @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="BrandUrlLanguage brand_id",
     *         required=true,
     *     ),
     *  @SWG\Response(
     *         response=200,
     *     )
     * )
     *
     * Display the specified resource.
     *
     * @param  int $brand_id
     * @return \Illuminate\Http\Response
     */
    public function getUrlsByBrandId(Request $request)
    {
        if ($request->id) {
            return response(
                $this->brandService->getUrlsByBrandId($request->id),
                200
            );
        }

        return response(400);
    }

    /**
     * @SWG\Get(
     *     path="/api/brands/url/brand/{brand_id}/language/{language_id}",
     *     description="Gets brandUrlLanguage by the brand_id and the language_id.",
     *     tags={"Brands"},
     *      @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="brand_url_language brand_id",
     *         required=true,
     *     ),
     *       @SWG\Parameter(
     *         name="language_id",
     *         in="path",
     *         type="integer",
     *         description="brand_url_language language_id",
     *         required=true,
     *     ),
     *  @SWG\Response(
     *         response=200,
     *     )
     * )
     *
     * Display the specified resource.
     *
     * @param  int $brand_id
     * @param  int $language_id
     * @return \Illuminate\Http\Response
     */
    public function getUrlsByBrandIdAndLanguageId(Request $request)
    {
        if ($request->brand_id && $request->language_id) {
            return response(
                $this->brandService->getUrlsByBrandIdAndLanguageId($request->brand_id, $request->language_id),
                200
            );
        }

        return response(400);
    }

    /**
     * @SWG\Get(
     *     path="/api/brands/url/brand/{brand_id}/language/{language_name}",
     *     description="Gets brandUrlLanguage by the brand_id and the language name",
     *     tags={"Brands"},
     *      @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="brand_url_language brand_id",
     *         required=true,
     *     ),
     *       @SWG\Parameter(
     *         name="name",
     *         in="path",
     *         type="string",
     *         description="table language, name",
     *         required=true,
     *     ),
     *  @SWG\Response(
     *         response=200,
     *     )
     * )
     *
     * Display the specified resource.
     *
     * @param  int $brand_id
     * @param  string $name
     * @return \Illuminate\Http\Response
     */
    public function getUrlsByBrandIdAndLanguage(Request $request)
    {
        if ($request->id && $request->name) {
            return response(
                $this->brandService->getUrlsByBrandIdAndLanguage($request->id, $request->name),
                200
            );
        }

        return response(400);
    }

    /**
     * @SWG\Put(
     *     path="brands/url/brand/{brand_id}/language/{language_id}/url/{url}",
     *     description="Updates the brand_url_language by given brand_id, language_id and the website url",
     *     tags={"Brands"},
     *      @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="brand_url_language brand_id",
     *         required=true,
     *     ),
     *      @SWG\Parameter(
     *         name="name",
     *         in="path",
     *         type="integer",
     *         description="Language Name",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="url",
     *         in="path",
     *         type="string",
     *         description="brand_url_language website URL.",
     *         required=true,
     *     ),
     *  @SWG\Response(
     *         response=200,
     *     )
     * )
     *
     * Update the specified resource.
     *
     * @param  int $brand_id
     * @param  int $language_id
     * @param  string $url
     * @return \Illuminate\Http\Response
     */
    public function updateBrandUrlLanguage(Request $request)
    {
        if ($request->id && $request->name && $request->url) {
            return response(
                $this->brandService->updateBrandUrlLanguage($request->id, $request->name, $request->url),
                200
            );
        }

        return response(400);
    }

    /**
     * @SWG\Put(
     *     path="brand/languages/url"
     *     description="Updates a list of URL's in Bulk.",
     *     tags={"Brands"},
     *      @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="brand_url_language brand_id",
     *         required=true,
     *     ),
     *      @SWG\Parameter(
     *         name="language_name",
     *         in="path",
     *         type="string",
     *         description="language_name",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="url",
     *         in="path",
     *         type="string",
     *         description="brand_url_language website URL.",
     *         required=true,
     *     ),
     *  @SWG\Response(
     *         response=200,
     *     )
     * )
     *
     * Update the specified resource.
     *
     * @param  int $brand_id
     * @param  int $language_name
     * @param  string $url
     * @return \Illuminate\Http\Response
     */
    public function updateUrlsLanguageList(Request $request)
    {
        if ($request->getContent()) {
            return response(
                $this->brandService->updateUrlsLanguageList(json_decode($request->getContent()), false),
                200
            );
        }
        return response(400);
    }

    /**
     * @SWG\Delete(
     *     path="/api/brands/url/brand/{brand_id}/language/{language_id}",
     *     description="Deletes the specified brand_url_language, with the brand_id and language_id.",
     *     tags={"Brands"},
     *      @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="brand_url_language Brand Id",
     *         required=true,
     *     ),
     *  @SWG\Parameter(
     *         name="language_id",
     *         in="path",
     *         type="integer",
     *         description="brand_url_language language Id",
     *         required=true,
     *     ),
     *  @SWG\Response(
     *         response=200,
     *     )
     * )
     *
     * Delete the specified resource.
     *
     * @param  int $brand_id
     * @param  int $language_id
     * @return \Illuminate\Http\Response
     */
    public function deleteBrandUrlLanguageByBrandIdAndLanguageName(Request $request)
    {
        if ($request->id && $request->name) {
            return response(
                $this->brandService->deleteBrandUrlLanguageByBrandIdAndLanguageName($request->id, $request->name),
                200
            );
        }

        return response(400);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getBrandParent($id)
    {
        if (!$id) {
            $statusCode = 400;
            return response($statusCode);
        }
        $statusCode = 200;
        $brand = Brand::findOrFail($id);

        return ["data" => [
            "brand" => $brand,
            "is_independent_hotel" => $brand->isIndependentHotel()
        ]];
    }

    /**
     * @param int $id
     */
    public function showInfo(int $id)
    {
        return $this->brandService->getBrandInfo($id);
    }

    public function updateInfo(PutBrandInfoDataType $request, int $id)
    {
        if (!$request->isInvalidData()) {
            return $this->brandService->updateBrandInfo($id, $request->brand);
        }
        Log::error('BrandController', ['message' => 'PutBrandInfoDataTypeValidationError', 'id' => $id, 'request' => $request->brand]);
        throw new InvalidRequestException('Bad fields in request body', [
            'inputs' => $request->getAttributes(),
            'errors' => $request->getValidatorMessages()
        ], 400);
    }

    public function archiveBrandInfo(PutBrandArchiveDataType $request)
    {
        if (!$request->isInvalidData()) {
            $response = $this->brandService->archiveBrand($request->status, $request->brand_id);
            return response()->json(json_decode($response, true), 200);
        } else {
            Log::error('BrandController', ['message' => 'PutBrandArchiveDataTypeValidationError', 'request' => $request->getAttributes()]);
            throw new InvalidRequestException('Bad fields in request body', [
                'inputs' => $request->getAttributes(),
                'errors' => $request->getValidatorMessages()
            ], 422);
        }
    }
}
