<?php

namespace App\Http\Controllers;

use App\Services\BrandAccessService;
use App\Http\Resources\Brands\BrandAccessTypeResource;
use App\Types\Brands\PutBrandAccessDataType;
use App\Exceptions\InvalidRequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BrandAccessController extends Controller
{
    protected $brandAccessService;

    public function __construct(BrandAccessService $brandAccessService)
    {
        $this->brandAccessService = $brandAccessService;
    }

    /**
     * @SWG\Get(
     *     path="/api/brands/{brand_id}/access/",
     *     description="Shows all codes to access to captive portal",
     *     tags={"Brands"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="HL brand id, identifier that will be used to relate all requested info",
     *         required=true,
     *     ),
     *
     *     @SWG\Parameter(
     *         name="type",
     *         in="query",
     *         type="string",
     *         description="Type of access to retrive (room, guest, premium, etc.)",
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
     * Display the specified resource.
     * @param $brand_id
     * @param Request $request
     */
    public function get($brand_id, Request $request)
    {
        $accessType = $request->get('type');
        $codes = $this->brandAccessService->get($brand_id, $accessType);

        return response($codes, '200');
    }

    /**
     * @SWG\Get(
     *     path="/api/brands/{brand_id}/access_types/",
     *     description="Shows login configurations of different access types of brand",
     *     tags={"Brands"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="HL brand id, identifier that will be used to relate all requested info",
     *         required=true,
     *     ),
     *
     *     @SWG\Response(
     *         response=200,
     *         description="success",
     *     )
     * )
     * Display the specified resource.
     * @param $brand_id
     */
    public function getAccessTypes($brand_id)
    {
        $accessTypes = $this->brandAccessService->getAccessTypes($brand_id);

        [$defaultAccessTypes, $brandAccessTypes] = $accessTypes->partition(function ($value) {
            return $value->brand_id == null;
        });

        $merged = $defaultAccessTypes->merge($brandAccessTypes);

        $recource = BrandAccessTypeResource::collection($merged);

        return $recource->response()->setStatusCode(200);
    }

    /**
     * @SWG\Put(
     *     path="/api/brands/{brand_id}/access_types/{access_type_id}",
     *     description="Update or create brand access type to activate or desactivate login",
     *     tags={"Protocols"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="Brand id",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="access_type_id",
     *         in="path",
     *         type="integer",
     *         description="Access type id",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="active",
     *         in="query",
     *         type="bool",
     *         description="Flag to activate or desactivate access type for brand",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="success",
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing Data"
     *     )
     * )
     *
     * Update the specified resource in storage.
     *
     * @param $brand_id
     * @param $product_id
     * @param $active
     * @return \Illuminate\Http\Response
     */
    public function putAccessTypes(PutBrandAccessDataType $data)
    {
        if ($data->isInvalidData()) {
            throw new InvalidRequestException('Bad fields in request body', $data->getAttributes(), 400);
        }

        $this->brandAccessService->putAccessTypes($data);

        return response(null, 204);
    }

    /**
     * @SWG\Put(
     *     path="/api/brands/{brand_id}/access",
     *     description="Update the specified brand access",
     *     tags={"Protocols"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="Brand id",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="codes",
     *         in="query",
     *         type="string",
     *         description="JSON array encoded of access codes",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="type",
     *         in="query",
     *         type="string",
     *         description="Type of access to retrive (room, guest, premium, etc.)",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="success",
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing Data"
     *     )
     * )
     *
     * Update the specified resource in storage.
     *
     * @param $brand_id
     * @param $product_id
     * @param $active
     * @return \Illuminate\Http\Response
     */

    public function store($brand_id, Request $request)
    {
        $accessType = $request->get('type');
        $codes = $request->get('codes');

        $codes = $this->brandAccessService->store($brand_id, $codes, $accessType);

        return response(null, 204);
    }
}
