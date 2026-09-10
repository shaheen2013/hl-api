<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Offer;
use App\Services\Products\LoyaltyProductService;
use App\Http\Resources\Reward\RewardResource;
use App\Services\Offers\OfferService;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    private $loyaltyProductService;
    private $offerService;

    public function __construct(LoyaltyProductService $loyaltyProductService, OfferService $offerService)
    {
        $this->loyaltyProductService = $loyaltyProductService;
        $this->offerService = $offerService;
    }

    /**
     * @SWG\Get(
     *     path="/api/offers/brand/{brand_id}/{lang}",
     *     description="Shows all offers following the parameters gave (
    <strong style='color:red'>int</strong> offer_id :identifier that will be used to relate all requested info with a concreted HL offer data
    <strong style='color:red'>string</strong> lang : first two locale characters (en = english, es = spanish, de = german, etc))
    the answer will follow the next structure:
    {
    <strong style='color:blue'>id</strong>: offer id,
    <strong style='color:blue'>start</strong>: start date for this offer,
    <strong style='color:blue'>end</strong>: end date for this offer,
    <strong style='color:blue'>image_url</strong>: image url associated with this offer,
    <strong style='color:blue'>booking_engine_code</strong>: promo code associated to this offer,
    <strong style='color:blue'>offer_lang</strong>{
    <strong style='color:blue'>name</strong>: offer name in the requested language,
    <strong style='color:blue'>description</strong>: offer description in the requested language,
    <strong style='color:blue'>conditions</strong>: offer conditions in the requested language,
    <strong style='color:blue'>lang</strong>: offer language
    },
    <strong style='color:blue'>offer_type</strong>: {
    <strong style='color:blue'>name</strong>: finality of the offer (example : discount, Free night, etc)
    },
    <strong style='color:blue'>category</strong>: {
    <strong style='color:blue'>name</strong>: Category of the offer (example: Food, Events, Relax)
    }
    }",
     *     tags={"Offer"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="HL offer id,
    identifier that will be used to relate all requested info with a concreted HL offer data",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *          name="lang",
     *         in="path",
     *         type="string",
     *         description="Offer lang,
    first two locale characters (en = english, es = spanish, de = german, etc)",
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
     *
     * Display the specified resource.
     * @param $brand_id
     * @param $lang
     * @return Offer[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getOfferByBrandId($brand_id, $lang)
    {
        $brand = Brand::findOrFail($brand_id)->load('brandType');
        $brandParent = Brand::find($brand->parent_id);
        $brand = $brand->brandInfo;
        $brandChildsIds = [$brand->id];

        $brandParentSelector = 'is NULL';
        if ($brandParent && $brandParent->brandInfo->cadenaHotel) {
            $brandChildsIds = [];
            $brandParentSelector = "=" . $brandParent->brandInfo->id;
            foreach ($brandParent->brandInfo->cadenaHotel as $hotel) {
                $brandChildsIds [] = $hotel->id;
            }
        }

        $offers =  Offer::whereIn('hotel_oferta.id_hotel', $brandChildsIds)
            ->orWhereRaw("hotel_oferta.id_cadena $brandParentSelector", [])
            ->get()->load('offerType', 'category');

        return response(RewardResource::collection($offers), 200);
    }

    /**
     * @SWG\Get(
     *     path="/api/brand/{brand_id}/loyalty-offers",
     *     description="Shows all loyalty offers following by brand and user lang (if not set returns english ones),
     *     tags={"Offer"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="HL brand identifier,
     *         required=true,
     *     ),
     *      @SWG\Parameter(
     *         name="lang",
     *         in="query",
     *         type="string",
     *         description="Review lang, first two locale characters (en = english, es = spanish, de = german, etc)",
     *         required=true,
     *     ),
     *      @SWG\Parameter(
     *         name="number_visits",
     *         in="query",
     *         type="integer",
     *         description="Offer related with a specific number of vists",
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
     *
     * Display the specified resource.
     * @param $brand_id
     * @return OfferGoal[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getLoyaltyOffers(Request $request, $brand_id)
    {
        $brand = Brand::findOrFail($brand_id)->load('brandType');

        if ($brand) {
            $loyaltyOffers = $this->loyaltyProductService->getOffers($brand, $request->get('lang'), $request->get('number_visits'));

            return response($loyaltyOffers, 200);
        }

        return response(null, 422);
    }

    /**
     * @SWG\Put(
     *     path="brand/{brand_id}/loyalty-offers",
     *     description="Update or create loyalty offers <br>
    <strong style='color:red'>int</strong> brand_id: identifier that will be used to relate all requested info with a concreted HL brand client data,
    <strong style='color:red'>int</strong> number_visits: number of visits to the chain to deliver the reward to the client,
    <strong style='color:red'>int</strong> reward_id: reward identifier to deliver to the client
    <strong style='color:red'>string</strong> reward_type: reawrd type (redeemable at the hotel, redeemable on the web, ...)
    <strong style='color:red'>int</strong> days_to_expire: days until the reward is delivered to expire
    <h3>The server will process put method without response (status code 204)</h3>",
     *     tags={"Offer"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="HL brand id, identifier that will be used to relate all requested info with a concreted HL brand client data",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="number_visits",
     *         in="query",
     *         type="integer",
     *         description="Number of visits to the chain to deliver the reward to the client",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="reward_id",
     *         in="query",
     *         type="integer",
     *         description="Reward identifier to deliver to the client",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="reward_type",
     *         in="query",
     *         type="string",
     *         description="Reawrd type (redeemable at the hotel, redeemable on the web, ...)",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="days_to_expire",
     *         in="query",
     *         type="integer",
     *         description="days until the reward is delivered to expire",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="success",
     *     ),
     *     @SWG\Response(
     *         response=409,
     *         description="Cannot update or create offer",
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing Data"
     *     )
     * )
     * Display the specified resource.
     * @param Request $request
     * @param $brand_id
     * @param $number_visits
     * @param $reward_id
     * @param $reward_type
     * @param $days_to_expire
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function saveLoyaltyOffers(Request $request, $brand_id, $offer_id = null)
    {
        $numberVisits = (int) $request->get('number_visits');
        $rewardID = (int) $request->get('reward_id');
        $rewardType = $request->get('reward_type');
        $daysToExpire = (int) $request->get('days_to_expire');

        if ($brand_id && isset($numberVisits) && $rewardID && $rewardType && $daysToExpire) {
            try {
                $loyaltyOffers = $this->loyaltyProductService->setOffers($offer_id, $brand_id, $numberVisits, $rewardID, $rewardType, $daysToExpire);
                return response($loyaltyOffers, 200);
            } catch (\Exception $e) {
                return response("Cannot update or create offer", 409);
            }
        }

        return response("Missing data", 422);
    }

    /**
     * @SWG\Delete(
     *     path="/api/brands/{brand_id}/offer/{offer_id}",
     *     description="Delete a specific loyalty offer<br>
    <strong style='color:red'>int</strong> brand_id: identifier that will be used to relate all requested info with a concreted HL brand client data,
    <strong style='color:red'>int</strong> offer_id: relates to an specific offer goal,
    <h3>The server will process delete method without response (status code 204)</h3>",
     *     tags={"Offer"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="HL brand id, identifier that will be used to relate all requested info with a concreted HL brand client data",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="offer_id",
     *         in="path",
     *         type="integer",
     *         description="offer goal id, identifier of a sppecific offer",
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
     * Display the specified resource.
     * @param $brand_id
     * @param $survey_question_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function deleteLoyaltyOffers($brand_id, $offer_id)
    {
        if ($brand_id && $offer_id) {
            $this->loyaltyProductService->deleteOffers($offer_id, $brand_id);
            return response(null, 204);
        }

        return response("Missing data", 422);
    }


    /**
     * @SWG\Get(
     *     path="/api/offers/{offer_id}/{lang}",
     *     description="Shows all offers following the parameters gave (
    <strong style='color:red'>int</strong> offer_id :identifier that will be used to relate all requested info with a concreted HL offer data
    <strong style='color:red'>string</strong> lang : first two locale characters (en = english, es = spanish, de = german, etc))
    the answer will follow the next structure:
    {
    <strong style='color:blue'>id</strong>: offer id,
    <strong style='color:blue'>start</strong>: start date for this offer,
    <strong style='color:blue'>end</strong>: end date for this offer,
    <strong style='color:blue'>image_url</strong>: image url associated with this offer,
    <strong style='color:blue'>booking_engine_code</strong>: promo code associated to this offer,
    <strong style='color:blue'>offer_lang</strong>{
    <strong style='color:blue'>name</strong>: offer name in the requested language,
    <strong style='color:blue'>description</strong>: offer description in the requested language,
    <strong style='color:blue'>conditions</strong>: offer conditions in the requested language,
    <strong style='color:blue'>lang</strong>: offer language
    },
    <strong style='color:blue'>offer_type</strong>: {
    <strong style='color:blue'>name</strong>: finality of the offer (example : discount, Free night, etc)
    },
    <strong style='color:blue'>category</strong>: {
    <strong style='color:blue'>name</strong>: Category of the offer (example: Food, Events, Relax)
    }
    }",
     *     tags={"Offer"},
     *     @SWG\Parameter(
     *         name="offer_id",
     *         in="path",
     *         type="integer",
     *         description="HL offer id,
    identifier that will be used to relate all requested info with a concreted HL offer data",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *          name="lang",
     *         in="path",
     *         type="string",
     *         description="Offer lang,
    first two locale characters (en = english, es = spanish, de = german, etc)",
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
     *
     * Display the specified resource.
     * @param $id
     * @param $lang
     * @return Offer[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function show(int $id, string $lang)
    {
        return $this->offerService->show($id, $lang);
    }

    public function getOfferAndLangs(int $id)
    {
        return $this->offerService->getOfferAndLangs($id);
    }

    public function delete(int $brand_id, int $offer_id)
    {
        $result = $this->offerService->delete($offer_id, $brand_id);

        if ($result) {
            return response(null, 204);
        } else {
            return response(null, 409);
        }
    }
}
