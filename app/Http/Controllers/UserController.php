<?php

namespace App\Http\Controllers;

use App\Types\Users\ExternalUsersDataType;
use App\User;
use App\Brand;
use Illuminate\Http\Request;
use App\Services\Users\UserService;
use App\Exceptions\InvalidRequestException;
use App\Types\Users\UserDataType;
use App\Types\Users\BulkUnsubscribeDataType;
use App\Jobs\ProcessBulkUnsubscribe;
use App\Http\Resources\Users\BrandUserPaginate;
use App\Http\Resources\Users\BrandUserExternalPaginate;
use App\Http\Resources\Users\BrandUserResource;
use App\Http\Resources\Users\BrandUserExternalResource;
use App\Http\Resources\Users\BrandUserByEmailResource;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @SWG\Get(
     *     path="/api/brands/{brand_id}/users",
     *     description="Paginate all users of a brand applyng a list of filters:<br>
     *     tags={"Brand Users"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="HL brand id, identifier that will be used to relate all requested info with a concreted HL brand client data",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="page",
     *         in="query",
     *         type="integer",
     *         description="Current page to paginate",
     *         required=false,
     *     ),
     *      @SWG\Parameter(
     *         name="page_items",
     *         in="query",
     *         type="integer",
     *         description="Number of items per page. (defautl: 100, limit: 500)",
     *         required=false,
     *     ),
     *     @SWG\Parameter(
     *         name="brands",
     *         in="query",
     *         type="string",
     *         description="List of brand ids to be used on search",
     *         required=false,
     *     ),
     *     @SWG\Parameter(
     *         name="language",
     *         in="query",
     *         type="string",
     *         description="User lang, first two locale characters (en = english, es = spanish, de = german, etc, default: en)",
     *         required=false,
     *     ),
     *     @SWG\Parameter(
     *         name="date_from",
     *         in="query",
     *         type="string",
     *         description="Only get users created from this date",
     *         required=false,
     *     ),
     *     @SWG\Parameter(
     *         name="date_to",
     *         in="query",
     *         type="string",
     *         description="Only get users created until this date",
     *         required=false,
     *     ),
     *     @SWG\Parameter(
     *         name="unsubscribed",
     *         in="query",
     *         type="boolean",
     *         description="Only get the unsubscribed or subscribed users",
     *         required=false,
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
     * Paginate brand users
     *
     * @param int $brandId
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index(Request $request, int $brandId, ExternalUsersDataType $externalUsersDataType)
    {
        // Get the flag if is external or not
        $isExternalApi = $request->get('externalApi') ?? false;

        if ($externalUsersDataType->isInvalidData()) {
            throw new InvalidRequestException(null, [
                'inputs' => $externalUsersDataType->getAttributes(),
                'errors' => $externalUsersDataType->getValidatorMessages()
            ]);
        }

        // Get brand users applyng filters
        $brandUsers = $this->userService->getBrandUsers($brandId, $externalUsersDataType);

        if ($isExternalApi) {
            return response(new BrandUserExternalPaginate($brandUsers), 200);
        } else {
            return response(new BrandUserPaginate($brandUsers), 200);
        }
    }

    /**
     * @SWG\Get(
     *     path="/api/users/{id}",
     *     description="Shows the required user. the answer will follow the next structure: { { <strong style='color:blue'>id</strong>: HL user id, <strong style='color:blue'>email</strong>: user email, <strong style='color:blue'>name</strong>: user name, <strong style='color:blue'>location</strong>: user locale, <strong style='color:blue'>lang</strong>: user lang, <strong style='color:blue'>birthday</strong>: user birth date, <strong style='color:blue'>gender</strong>: user gender (male or female), <strong style='color:blue'>email_result</strong>: evaluation of the validity of the email (Undeliverable, Risky, Deliverable), <strong style='color:blue'>unsubscribed</strong>: User subscription to HL notifications 1=yes, 0=no, <strong style='color:blue'>user_facebook</strong>: { <strong style='color:blue'>name</strong>: user name obtained by facebook, <strong style='color:blue'>gender</strong>: user gender obtained by facebook, <strong style='color:blue'>age</strong>: user age obtained by facebook, <strong style='color:blue'>locationID</strong>: user facebook location id, <strong style='color:blue'>locationName</strong>: user location name obtained by facebook, <strong style='color:blue'>locale</strong>: user locale obtained by facebook, <strong style='color:blue'>facebook_img</strong>: user image url obtained by facebook, <strong style='color:blue'>friends</strong>: number of user friends, <strong style='color:blue'>birthday</strong>: user birthday obtained by facebook } }",
     *     tags={"HL User"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         type="integer",
     *         description="HL user id, identifier that will be used to relate all requested info with a concreted HL user data",
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
        return User::findOrFail($id)->load('userFacebook');
    }

    /**
     * @SWG\Post(
     *     path="/api/users",
     *     description="Store the supplied user. the answer will follow the next structure: { { <strong style='color:blue'>id</strong>: HL user id, <strong style='color:blue'>email</strong>: user email, <strong style='color:blue'>name</strong>: user name, <strong style='color:blue'>location</strong>: user locale, <strong style='color:blue'>lang</strong>: user lang, <strong style='color:blue'>birthday</strong>: user birth date, <strong style='color:blue'>gender</strong>: user gender (male or female), <strong style='color:blue'>email_result</strong>: evaluation of the validity of the email (Undeliverable, Risky, Deliverable), <strong style='color:blue'>unsubscribed</strong>: User subscription to HL notifications 1=yes, 0=no, <strong style='color:blue'>user_facebook</strong>: { <strong style='color:blue'>name</strong>: user name obtained by facebook, <strong style='color:blue'>gender</strong>: user gender obtained by facebook, <strong style='color:blue'>age</strong>: user age obtained by facebook, <strong style='color:blue'>locationID</strong>: user facebook location id, <strong style='color:blue'>locationName</strong>: user location name obtained by facebook, <strong style='color:blue'>locale</strong>: user locale obtained by facebook, <strong style='color:blue'>facebook_img</strong>: user image url obtained by facebook, <strong style='color:blue'>friends</strong>: number of user friends, <strong style='color:blue'>birthday</strong>: user birthday obtained by facebook } }",
     *     tags={"HL User"},
     *     @SWG\Parameter(
     *         name="email",
     *         in="query",
     *         type="string",
     *         description="HL user email",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="nombre",
     *         in="query",
     *         type="string",
     *         description="HL user name",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="sexo",
     *         in="query",
     *         type="string",
     *         description="HL user gender",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="fecha_nacimiento",
     *         in="query",
     *         type="string",
     *         description="HL user birthday",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="location",
     *         in="query",
     *         type="string",
     *         description="HL user location (completed locale)",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="locale",
     *         in="query",
     *         type="string",
     *         description="HL user locale ()",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="email_result",
     *         in="query",
     *         type="string",
     *         description="HL user  email email_result (obtained from quick box)",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="sendex",
     *         in="query",
     *         type="string",
     *         description="HL user  email sendex (obtained from quick box)",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="unsubscribed",
     *         in="query",
     *         type="string",
     *         description="HL user is unsubscribed 1(yes) or 0 (no)",
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
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->userService->createUser($request->all());
    }

    public function updateOrCreate(UserDataType $request, int $brand_id)
    {
        if (!$request->isInvalidData()) {
            $result = $this->userService->updateOrCreate($request->data, $brand_id);
            return response(
                [
                    "user" => $result['user'],
                    "isNew" => $result['isNew']
                ]
            );
        }
        Log::error('UserController', ['message' => 'UserDataType validation error', 'brand_id' => $brand_id, "errors" => $request->getErrors()]);
        throw new InvalidRequestException('Bad fields in request body', $request->getErrors()->toArray(), 400);
    }

    /**
     * @SWG\Get(
     *     path="/api/users/{email}/{brand_id}",
     *     description="Shows the required user. the answer will follow the next structure: { { <strong style='color:blue'>id</strong>: HL user id, <strong style='color:blue'>email</strong>: user email, <strong style='color:blue'>name</strong>: user name, <strong style='color:blue'>location</strong>: user locale, <strong style='color:blue'>lang</strong>: user lang, <strong style='color:blue'>birthday</strong>: user birth date, <strong style='color:blue'>gender</strong>: user gender (male or female), <strong style='color:blue'>email_result</strong>: evaluation of the validity of the email (Undeliverable, Risky, Deliverable), <strong style='color:blue'>unsubscribed</strong>: User subscription to HL notifications 1=yes, 0=no, <strong style='color:blue'>user_facebook</strong>: { <strong style='color:blue'>name</strong>: user name obtained by facebook, <strong style='color:blue'>gender</strong>: user gender obtained by facebook, <strong style='color:blue'>age</strong>: user age obtained by facebook, <strong style='color:blue'>locationID</strong>: user facebook location id, <strong style='color:blue'>locationName</strong>: user location name obtained by facebook, <strong style='color:blue'>locale</strong>: user locale obtained by facebook, <strong style='color:blue'>facebook_img</strong>: user image url obtained by facebook, <strong style='color:blue'>friends</strong>: number of user friends, <strong style='color:blue'>birthday</strong>: user birthday obtained by facebook } }",
     *     tags={"HL User by email"},
     *     @SWG\Parameter(
     *         name="email",
     *         in="path",
     *         type="string",
     *         description="HL user email, identifier that will be used to relate all requested info with a concreted HL user data",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="brand id",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="success",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="User or brand not found"
     *     )
     * )
     * Display the specified resource.
     *
     * @param $email
     * @param $brand_id
     * @return \Illuminate\Http\Response
     */
    public function getUserByEmailAndBrandId($email, $brand_id)
    {
        $brand = Brand::findOrFail($brand_id);
        $user = User::with('userFacebook')
            ->where('email', $email)
            ->first();

        if (!$user) {
            return response('User not found', 204);
        }

        if ($brand->hotel_id) {
            $user_hotel = $user->userHotels()
                ->where('id_hotel', $brand->hotel_id)
                ->first();

            if (!$user_hotel) {
                return response('User not found', 204);
            }

            return response($user, 200);
        }

        if ($brand->chain_id) {
            $user_chain = $user->userHotels()
                ->where('id_cadena', $brand->chain_id)
                ->first();

            if (!$user_chain) {
                return response('User not found', 204);
            }

            return response($user, 200);
        }

        return response('User not found', 204);
    }

    public function getVisitedBrandsByEmail($userEmail)
    {
        $userBrands = $this->userService->getVisitedBrandsByEmail($userEmail);
        $transformedData = BrandUserByEmailResource::collection($userBrands);

        return response($transformedData, '200');
    }
   

    /**
     * @SWG\Get(
     *     path="/api/brands/{brand_id}/users/{user_id}",
     *     description="Shows the requested user.",
     *     tags={"Brand user"},
     *     @SWG\Parameter(
     *         name="user_id",
     *         in="path",
     *         type="integer",
     *         description="HL user id, identifier that will be used to relate all requested info with a concreted HL user data",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="brand id",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="success",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="User or brand not found"
     *     )
     * )
     * Display the specified resource.
     *
     * @param $brand_id
     * @param $user_id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function getBrandUser(Request $request, int $brandId, int $userId)
    {
        // Get the flag if is external or not
        $isExternalApi = $request->get('externalApi') ?? false;

        $user = $this->userService->getBrandUser($brandId, $userId);

        if ($isExternalApi) {
            return new BrandUserExternalResource(
                $this->userService->getBrandUser($brandId, $userId)
            );
        } else {
            return new BrandUserResource(
                $this->userService->getBrandUser($brandId, $userId)
            );
        }
    }

    /**
     * @SWG\Put(
     *     path="/api/brands/{brand_id}/users/{user_id}/unsubscribe",
     *     description="Unsubscribes user",
     *     tags={"Unsubscribe user"},
     *     @SWG\Parameter(
     *         name="user_id",
     *         in="path",
     *         type="integer",
     *         description="user id",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="brand id",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="success",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="User or brand not found"
     *     )
     * )
     * Display the specified resource.
     *
     * @param $brand_id
     * @param $user_id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function unsubscribe(Request $request, int $brandId, int $userId)
    {
        Log::info('UserController', ['message' => 'Unsubscribing user by endpoint', 'userId' => $userId, 'brandId' => $brandId]);
        $data = json_decode($request->getContent(), true);
        $this->userService->unsubscribe($userId, $brandId, $data);
    }

    /**
     * @SWG\Put(
     *     path="/api/brands/{brand_id}/bulk-unsubscribe",
     *     description="Unsubscribes user",
     *     tags={"Unsubscribe user"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="brand id",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="user_ids",
     *         in="query",
     *         type="string",
     *         description="Array of user_id to unsubscribe",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *         response=202,
     *         description="success",
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Invalid data"
     *     )
     * )
     * Display the specified resource.
     *
     * @param $brand_id
     * @param $user_ids
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function bulkUnsubscribe(BulkUnsubscribeDataType $data)
    {
        if ($data->isInvalidData()) {
            throw new InvalidRequestException('Bad fields in request body', $data->getErrors()->toArray(), 400);
        }

        $userBrands = $this->userService->getRelatedUsersInBrand($data->brandId, $data->userIds);

        if (Arr::get($userBrands, 'unsubscribedUsers') || Arr::get($userBrands, 'usersNotInBrand')) {
            Log::warning("Some users need to be deleted on bulk unsubscribe", [
                "Users To Update"       => Arr::get($userBrands, 'usersToUpdate'),
                "Unsubscribed Users"    => Arr::get($userBrands, 'unsubscribedUsers'),
                "Users Not In Brand"    => Arr::get($userBrands, 'usersNotInBrand')
            ]);
        }

        ProcessBulkUnsubscribe::dispatch($data->brandId, Arr::get($userBrands, 'usersToUpdate'))->onQueue('reports');

        return response(null, 202);
    }

    public function getBrandUserSubscriptions(int $brandId, int $userId)
    {
        $subscriptions =  $this->userService->getBrandUserSubscriptions($brandId, $userId);

        if (!$subscriptions) {
            return response()->json(['message' => 'Subscriptions not found'], 404);
        }

        return response()->json($subscriptions);
    }
}
