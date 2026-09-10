<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Hotel;
use App\Cadena;
use Validator;
use DB;
use Hash;
use App\Services\Cognito;
use App\Services\Products\ProductService;
use App\Services\BrandProductService;

class WidgetController extends Controller
{
    const WIDGET_PRODUCT = 'widget';

    private $password;
    private $brand_uuid;
    private $product;
    private $brandProductService;
    protected $cognito;

    public function __construct(Cognito $cognito, ProductService $productService, BrandProductService $brandProductService)
    {
        $this->cognito = $cognito;
        $this->product = $productService->getByName(self::WIDGET_PRODUCT);
        $this->brandProductService = $brandProductService;
    }

    /**
     * @SWG\Post(
     *     path="/api/register/widget",
     *     description="Register a new or existing brand with widget product in to cognito pool",
     *     tags={"Register"},
     *     @SWG\Parameter(
     *         name="email",
     *         in="query",
     *         type="string",
     *         description="Brand email contact",
     *         required=true,
     *     ),
     *      @SWG\Parameter(
     *         name="password",
     *         in="query",
     *         type="string",
     *         description="Brand password",
     *         required=true,
     *     ),
     *      @SWG\Parameter(
     *         name="type",
     *         in="query",
     *         type="string",
     *         description="Type of brand",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Return brand data",
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing Data"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Chain not found"
     *     ),
     *     @SWG\Response(
     *         response=409,
     *         description="User already exists"
     *     )
     * )
     *
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = $this->checkRequest($request);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $this->password = $request->password;
        $request_data = $request->all();
        $request_data['password'] = "";

        $method = 'check' . ucfirst($request_data['type']);
        unset($request_data['type']);

        try {
            $response = $this->{$method}($request_data);
        } catch (\BadMethodCallException $e) {
            return response('Type not allowed', 422);
        }

        return $response;
    }

    private function checkHotel(array $request_data)
    {
        $hotels = Hotel::where('email', $request_data['email']);
        $brand = null;
        $hotel = null;
        $hotel_id = null;

        if ($hotels->count() > 1) {
            return $this->checkChain($request_data);
        } elseif ($hotels->count() == 1) {
            $hotel = $hotels->first();
        } else {
            return response('Hotel not found', 404);
        }

        if ($hotel) {
            $hotel->load('brand');
            $brand = $hotel->brand;
        }

        if ($brand) {
            $this->brandProductService->set(
                [
                    'brand_id'   => $brand->id,
                    'product_id' => $this->product->id,
                    'active'     => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );

            $this->brand_uuid = $brand->uuid;
        } else {
            return response('Could not register correctly', 500);
        }

        return $this->createCognitoAccount($request_data);
    }

    private function checkChain(array $request_data)
    {
        $chain = Cadena::where('email', $request_data['email'])->first();

        if (!$chain) {
            return response('Chain not found', 404);
        }

        $chain->load('brandChilds');
        $brand_product = [];

        foreach ($chain->brandChilds as $childBrand) {
            $brand_product[] = [
                'brand_id'   => $childBrand->id,
                'product_id' => $this->product->id,
                'active'     => 1,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        $this->brandProductService->insertIgnore($brand_product);

        if (isset($chain->id)) {
            $chain->load('brand');
            $brand = $chain->brand;
            $this->brand_uuid = $brand->uuid;
        }

        return $this->createCognitoAccount($request_data);
    }

    private function checkRequest(Request $request, $extra_rules = [])
    {
        $credentials = $request->only('type', 'email', 'password');

        $rules = [
            'type'     => 'required|max:255',
            'password' => 'required|max:255',
            'email'    => 'required|max:255|unique:users'
        ];

        $rules = array_merge($rules, $extra_rules);
        $validator = Validator::make($credentials, $rules);

        return $validator;
    }

    private function createCognitoAccount(array $request_data)
    {
        try {
            $this->cognito->setUsername($request_data['email']);
            $this->cognito->setPassword($this->password);
            $this->cognito->setUuid($this->brand_uuid);
            $exists = !$this->cognito->setCognitoUserIfNotExists();
        } catch (\Exception $e) {
            return response('Cannot register the cognito user', 503);
        }

        if ($exists) {
            return response('The user could not be registered or the user already exists.', 409);
        }

        return response($request_data, 200);
    }
}
