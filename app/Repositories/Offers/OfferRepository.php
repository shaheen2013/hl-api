<?php

namespace App\Repositories\Offers;

use App\Brand;
use App\ChainOfferReferral;
use App\Hotel;
use App\HotelOfferReferral;
use App\Offer;
use Illuminate\Support\Facades\Log;

class OfferRepository implements OfferRepositoryInterface
{
    private $offer;
    const CONSTRAINT_SQL_CODE = "23000";

    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
    }

    public function show($id, $lang)
    {
        $query = $this->offer::with(array('offerLang' => function ($query) use ($lang) {
            $langRequested = $query->where('hotel_oferta_lang.lang', "$lang");

            if ($langRequested->get()->isEmpty()) {
                $query->orWhere('hotel_oferta_lang.lang', 'en');
            }
        }))
        ->where('hotel_oferta.id', '=', $id)
        ->first();

        if ($query) {
            $query->load('offerType', 'category');
        }

        return response($query, 200);
    }

    public function getOfferAndLangs($id)
    {
        return $this->offer::findOrFail($id)->load('offerLang');
    }

    public function store($offer)
    {
        return $this->offer::create($offer);
    }

    public function update($offerParams, $id)
    {
        $offer = Offer::findOrFail($id);
        $offer->update($offerParams);

        return $offer;
    }

    public function delete($offer_id, $brand_id, $brand_type = 'hotel')
    {
        try {
            return $this->offer::where('id_' . $brand_type, $brand_id)
                ->where('id', $offer_id)
                ->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[0] === self::CONSTRAINT_SQL_CODE) {
                Log::warning("The offer cannot be removed because it is linked to a reward", [
                    "error" => $e,
                    "brand_type" => $brand_type,
                    "brand_id" => $brand_id
                ]);
            } else {
                Log::error("Unexpected error deleting offer", [
                    "error" => $e,
                    "brand_type" => $brand_type,
                    "brand_id" => $brand_id
                ]);
            }
        }
    }

    public function checkOfferFromHotel($offer_id, $hotel_id)
    {
        $offer = $this->offer::where('id_hotel', $hotel_id)
            ->where('id', $offer_id)->first();

        $offer === null ? $offerIsFromBrand = false : $offerIsFromBrand = true;

        return $offerIsFromBrand;
    }

    public function offerIsFromChain($offer_id)
    {
        $offer = $this->offer::where('id', $offer_id)->first();
        $offer->id_cadena != 0 ? $offerIsFromChain = true : $offerIsFromChain = false;

        return $offerIsFromChain;
    }
}
