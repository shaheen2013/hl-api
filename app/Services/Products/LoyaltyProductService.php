<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 14/03/2019
 * Time: 15:58
 */

namespace App\Services\Products;

use App\Brand;
use App\LoyaltyConfig;
use App\OfferGoal;
use App\Product;
use App\Http\Resources\ProductsConfigurations\LoyaltyProductConfiguration;
use Illuminate\Http\Request;
use App\Http\Resources\Loyalty\OffersResource;
use Illuminate\Support\Arr;

class LoyaltyProductService implements HLProduct
{
    public function setConfiguration($brand_id, Request $request)
    {
        $brand = Brand::findOrFail($brand_id);
        LoyaltyConfig::updateOrCreate(
            [
                'brand_id' =>  $brand_id,
            ],
            [
                'summary_active' => $request->summaryActive,
                'summary_send_days' => $request->summarySendDays,
                'summary_send_hours' => $request->summarySendHours
            ]
        );
        return new LoyaltyProductConfiguration($brand);
    }

    public function getConfiguration($brand_id)
    {
        $brand = Brand::findOrFail($brand_id);
        return new LoyaltyProductConfiguration($brand);
    }

    public function setDefaultConfiguration($brand_id, $active)
    {
        $brand = Brand::findOrFail($brand_id);
        $hotel = $brand->hotel;
        if ($active && $hotel) {
            if (!$hotel->loyalty_emails && $hotel->hotelSatisfaction) {
                $hotel->loyalty_emails = $hotel->hotelSatisfaction->warning_email;
                $hotel->save();
            }
        }
    }

    public function getOffers($brand, $lang, $numberVisits = null)
    {
        $requestedBrands = !empty($brand->parent_id) ?
            [$brand->id, $brand->parent_id] :
            Brand::where('parent_id', $brand->id)->orWhere('id', $brand->id)->get()->pluck('id');

        $offersGoal = OfferGoal::where(function ($query) use ($numberVisits, $requestedBrands) {
            if ($numberVisits) {
                $offerRequested = $query->whereIn("brand_id", $requestedBrands)->where("n_triggers", $numberVisits);

                if ($offerRequested->get()->isEmpty()) {
                    $query->orWhere('n_triggers', 0);
                }
            }
        })->whereIn("brand_id", $requestedBrands)->get()->load('offer.offerLang', 'brand');

        if (!empty($brand->parent_id)) {
            $ownOffers = array_filter($offersGoal->toArray(), function ($val) use ($brand) {
                return data_get($val, 'brand_id') == $brand->id;
            });

            $offersGoal = $offersGoal->filter(function ($val) use ($ownOffers, $brand) {
                return data_get($val, 'brand_id') == $brand->id ||
                    !in_array(
                        data_get($val, 'n_triggers'),
                        Arr::pluck($ownOffers, 'n_triggers')
                    );
            });
        }

        return OffersResource::collection($offersGoal)->lang($lang);
    }

    public function setOffers($offerID, $brandID, $numberVisits, $rewardID, $rewardType, $daysToExpire)
    {

        $offerInfo = [
            'brand_id' => $brandID,
            'n_triggers' => $numberVisits,
            'offer_id' => $rewardID,
            'offer_type' => $rewardType,
            'days_to_expire' => $daysToExpire
        ];

        if ($offerID) {
            OfferGoal::where('id', $offerID)->update($offerInfo);
        } else {
            $loyaltyProduct = Product::where('producto', 'loyalty')->first();
            $offerInfo['product_id'] = $loyaltyProduct->id;

            $offerID = OfferGoal::insertGetId($offerInfo);
        }

        return $offerID;
    }

    public function deleteOffers($offerID, $brandID)
    {
        OfferGoal::where([
            ["id", $offerID],
            ['brand_id', $brandID]
        ])->delete();
    }

    public function getSummaryOffers($offersGoal)
    {
        return array_map(function ($offer) {
            $offer = $offer->toArray();
            return ["visits" => Arr::get($offer, 'visits'), "offer" => Arr::get($offer, 'offer.title'), "brand_id" => Arr::get($offer, 'brand.id')];
        }, $offersGoal->toArray());
    }
}
