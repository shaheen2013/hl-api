<?php

namespace App\Http\Controllers;

use App\Exceptions\OfferWifi\OfferWifiInvalidRequestException;
use App\Rules\OfferWifiDefault;
use Illuminate\Http\Request;
use App\Services\OfferWifi\OfferWifiService;
use Illuminate\Support\Facades\Validator;

class OfferWifiController extends Controller
{
    private $offerWifiService;

    public function __construct(OfferWifiService $offerWifiService)
    {
        $this->offerWifiService = $offerWifiService;
    }

    /**
     * @SWG\Get(
     *     path="/api/offers/wifi/brand/{id}/{lang}",
     *     description="Shows all wifi offers following by brand and user lang (if not set returns all)",
     *     tags={"OfferWifi"},
     * @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         type="integer",
     *         description="HL brand identifier",
     *         required=true,
     *     ),
     * @SWG\Parameter(
     *         name="lang",
     *         in="path",
     *         type="string",
     *         description="Offer language",
     *         required=false,
     *     ),
     * @SWG\Parameter(
     *         name="valid_from",
     *         in="query",
     *         type="string",
     *         format="date",
     *         description="Initial period for the wifi offer",
     *         required=false,
     *     ),
     * @SWG\Parameter(
     *         name="valid_to",
     *         in="query",
     *         type="string",
     *         format="date",
     *         description="Final period for the wifi offer",
     *         required=false,
     *     ),
     * @SWG\Response(
     *         response=200,
     *         description="success",
     *     ),
     * @SWG\Response(
     *         response=422,
     *         description="Missing Data"
     *     )
     * )
     *
     * Display the specified resource.
     * @param $brandID
     * @param $lang
     * @return OfferWifi[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */

    public function show(int $brandID, string $lang = null, Request $request)
    {
        return $this->offerWifiService->getBrandOffersWifi($brandID, $lang, $request->valid_from, $request->valid_to, $request->customer_type);
    }

    /**
     *
     * @OA\RequestBody(
     *     request="BrandWifiOffers",
     *     description="Set or update wifi offers",
     *     required=true,
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     )
     * )
     */

    /**
     * @SWG\Post(
     *     path="/api/offers/wifi/brand/{id}",
     *     description="Insert the specified resource.",
     *     tags={"OfferWifi"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         type="integer",
     *         description="HL brand identifier",
     *         required=true,
     *     ),
     * @SWG\Response(
     *         response=204,
     *         description="update success, no content",
     *     ),
     * @SWG\Response(
     *         response=201,
     *         description="success, created",
     *     ),

     * @SWG\Response(
     *         response=400,
     *         description="Insert data error"
     *     )
     * )
     * @param $brandID
     * @return OfferWifi[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */

    public function createOrUpdate(int $brandID, Request $request)
    {
        $wifiOffers = $request->all();
        if ($this->notValidRequest($wifiOffers)) {
            throw new OfferWifiInvalidRequestException('Bad fields in request body', $wifiOffers);
        }

        return $this->offerWifiService->setOffersWifi($brandID, $wifiOffers);
    }

    /**
     * @SWG\Delete(
     *     path="/api/offers/wifi/{id}",
     *     description="Delete the specified resource.",
     *     tags={"OfferWifi"}
     * @SWG\Response(
     *         response=204,
     *         description="success, no content",
     *     ),
     * @SWG\Response(
     *         response=417,
     *         description="Expectation failed"
     *     )
     * )
     * @param $id
     * @return void
     */

    public function delete(int $brandWifiOfferID)
    {
        if (!$this->offerWifiService->delete($brandWifiOfferID)) {
            response([], 404);
        };
    }

    /***
     * @param array $wifiOffers
     * @return bool
     */

    private function notValidRequest(array $wifiOffers): bool
    {
        $data = ['data' => $wifiOffers];
        $validator = Validator::make($data, [
            'data.*.id'                 => 'sometimes|required|integer',
            'data.*.accommodated'       => 'required|boolean',
            'data.*.non_accommodated'   => 'required|boolean',
            'data.*.offer_id'           => 'required|integer',
            'data.*.condition'          => 'required|in:facebook_login,always,facebook_share',
            'data.*.offer_type'         => 'required|in:inmediate,web',
            'data.*.period'             => 'required|integer',
            'data.*.valid_from'         => 'nullable|date',
            'data.*.valid_to'           => 'nullable|date',
            'data.*.is_default'         => 'required|boolean'
        ]);

        return $validator->fails();
    }
}
