<?php

namespace Tests\Feature;

use App\Exceptions\DeniedAccessResourceException;
use App\Hotel;
use App\Cadena;
use App\CadenaHotel;
use App\Offer;
use App\OfferLang;
use App\OfferGoal;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

class OfferControllerTest extends TestCase
{
    public function testOffersAreShowCorrectly()
    {
        $hotel = factory(Hotel::class)->create();

        $offer = factory(Offer::class)->create([
            'id_hotel' => $hotel->id,
            'id_cadena' => 0
        ]);

        factory(OfferLang::class)->create([
            'id_oferta' => $offer->id
        ]);

        $this->json('GET', '/api/offers/' . $offer->id . '/en', [])
            ->assertStatus(200)
            ->assertJson([
                'booking_engine_code' => $offer->booking_engine_code,
            ]);
    }

    public function testOffersByBrandIdCorrectly()
    {
        $hotel = factory(Hotel::class)->create();

        $offer = factory(Offer::class)->create([
            'id_hotel' => $hotel->id,
            'id_cadena' => 0
        ]);

        $offerLang = factory(OfferLang::class)->create([
            'id_oferta' => $offer->id
        ]);

        $this->json('GET', '/api/offers/brand/' . $hotel->brand_id . '/en', [])
            ->assertStatus(200)
            ->assertJson([[
                'id' => $offer->id,
                "booking_engine_code" => $offer->booking_engine_code,
                "start" => $offer->start,
                "end" => $offer->end,
                "image_url" => $offer->iamge_url,
                "offer_type" => $offer->offer_type,
                "category" => $offer->category,
                "offer_lang" => [
                    "lang" => "en",
                    "name" => $offerLang->nombre,
                    "description" => $offerLang->descripcion,
                    "conditions" => $offerLang->condiciones
                ]
            ]]);
    }

    public function testOffersByParentBrandIdCorrectly()
    {
        $chain = factory(Cadena::class)->create();
        $hotel = factory(Hotel::class)->create();
        $hotel2 = factory(Hotel::class)->create();

        factory(CadenaHotel::class)->create([
            'id_cadena' => $chain->id,
            'id_hotel'  => $hotel->id
        ]);
        factory(CadenaHotel::class)->create([
            'id_cadena' => $chain->id,
            'id_hotel'  => $hotel2->id
        ]);

        $offer = factory(Offer::class)->create([
            'id_hotel' => 0,
            'id_cadena' => $chain->id
        ]);

        $offer2 = factory(Offer::class)->create([
            'id_hotel' => 0,
            'id_cadena' => $chain->id
        ]);

        $offerLang = factory(OfferLang::class)->create([
            'id_oferta' => $offer->id
        ]);

        $offerLang2 = factory(OfferLang::class)->create([
            'id_oferta' => $offer2->id
        ]);


        $offerLang2Es = factory(OfferLang::class)->create([
            'id_oferta' => $offer2->id,
            'lang' => 'es'
        ]);


        $this->json('GET', '/api/offers/brand/' . $hotel->brand_id . '/es', [])
            ->assertStatus(200)
            ->assertJson([
                [
                    'id' => $offer->id,
                    "booking_engine_code" => $offer->booking_engine_code,
                    "start" => $offer->start,
                    "end" => $offer->end,
                    "image_url" => $offer->iamge_url,
                    "offer_type" => $offer->offer_type,
                    "category" => $offer->category,
                    "offer_lang" => [
                        "lang" => "en",
                        "name" => $offerLang->nombre,
                        "description" => $offerLang->descripcion,
                        "conditions" => $offerLang->condiciones
                    ]
                ],
                [
                    'id' => $offer2->id,
                    "booking_engine_code" => $offer2->booking_engine_code,
                    "start" => $offer2->start,
                    "end" => $offer2->end,
                    "image_url" => $offer2->iamge_url,
                    "offer_type" => $offer2->offer_type,
                    "category" => $offer2->category,
                    "offer_lang" => [
                        "lang" => "es",
                        "name" => $offerLang2Es->nombre,
                        "description" => $offerLang2Es->descripcion,
                        "conditions" => $offerLang2Es->condiciones
                    ]
                ]
            ]);
    }


    public function testOfferAndLangsCorrectly()
    {
        $hotel = factory(Hotel::class)->create();

        $offer = factory(Offer::class)->create([
            'id_hotel' => $hotel->id,
            'id_cadena' => 0
        ]);

        $offerLangEn = factory(OfferLang::class)->create([
            'id_oferta' => $offer->id
        ]);

        $offerLangEs = factory(OfferLang::class)->create([
            'id_oferta' => $offer->id,
            'lang' => 'es'
        ]);

        $this->json('GET', '/api/offers/' . $offer->id, [])
            ->assertStatus(200)
            ->assertJson([
                'id' => $offer->id,
                "booking_engine_code" => $offer->booking_engine_code,
                "start" => $offer->start,
                "end" => $offer->end,
                "image_url" => $offer->image_url,
                "offer_lang" => [
                    [
                        "lang" => "en",
                        "name" => $offerLangEn->nombre,
                        "description" => $offerLangEn->descripcion,
                        "conditions" => $offerLangEn->condiciones
                    ],
                    [
                        "lang" => "es",
                        "name" => $offerLangEs->nombre,
                        "description" => $offerLangEs->descripcion,
                        "conditions" => $offerLangEs->condiciones
                    ]
                ]
            ]);
    }

    public function testGetLoyaltyOffersAreShowCorrectly()
    {
        $hotel = factory(Hotel::class)->create();

        $offer = factory(Offer::class)->create([
            'id_hotel' => $hotel->id,
            'id_cadena' => 0
        ]);

        $offerGoal = factory(OfferGoal::class)->create([
            'brand_id' => $hotel->brand_id,
            'offer_id' => $offer->id
        ]);

        factory(OfferLang::class)->create([
            'id_oferta' => $offer->id
        ]);

        $this->json('GET', '/api/brand/' . $hotel->brand_id . '/loyalty-offers', [])
            ->assertStatus(200)
            ->assertJson([[
                'brand' => ["id" => $hotel->brand_id],
                'offer' => ["id" => $offer->id]
            ]]);
    }

    public function testSetLoyaltyOffersAreCreatedCorrectly()
    {
        $hotel = factory(Hotel::class)->create();

        $offer = factory(Offer::class)->create([
            'id_hotel' => $hotel->id,
            'id_cadena' => 0
        ]);

        $this->json('PUT', '/api/brand/' . $hotel->brand_id . '/offer', [
            'number_visits' => 1,
            'reward_id' => $offer->id,
            'reward_type' => 'web',
            'days_to_expire' => 1,
        ])->assertStatus(200);

        $offerCreated = OfferGoal::where('brand_id', $hotel->brand_id)->first();

        $this->assertNotEquals($offerCreated, null);
    }

    public function testSetLoyaltyOffersAreUpdatedCorrectly()
    {
        $hotel = factory(Hotel::class)->create();

        $offer = factory(Offer::class)->create([
            'id_hotel' => $hotel->id,
            'id_cadena' => 0
        ]);

        $this->json('PUT', '/api/brand/' . $hotel->brand_id . '/offer', [
            'number_visits' => 1,
            'reward_id' => $offer->id,
            'reward_type' => 'web',
            'days_to_expire' => 1,
        ])->assertStatus(200);

        $loyaltyOffer = OfferGoal::where('brand_id', $hotel->brand_id)->first();

        //Update an offer
        $this->json('PUT', '/api/brand/' . $hotel->brand_id . '/offer/' . $loyaltyOffer->id, [
            'number_visits' => 2,
            'reward_id' => $offer->id,
            'reward_type' => 'web',
            'days_to_expire' => 2,
        ])->assertStatus(200);

        $this->assertEquals($loyaltyOffer->fresh()->n_triggers, 2);
        $this->assertEquals($loyaltyOffer->fresh()->days_to_expire, 2);
    }

    public function testSetLoyaltyOffersIsNotSavedWithConstraint()
    {
        $hotel = factory(Hotel::class)->create();

        $offer = factory(Offer::class)->create([
            'id_hotel' => $hotel->id,
            'id_cadena' => 0
        ]);

        $this->json('PUT', '/api/brand/' . $hotel->brand_id . '/offer', [
            'number_visits' => 1,
            'reward_id' => $offer->id,
            'reward_type' => 'web',
            'days_to_expire' => 1,
        ])->assertStatus(200);

        $this->json('PUT', '/api/brand/' . $hotel->brand_id . '/offer', [
            'number_visits' => 1,
            'reward_id' => $offer->id,
            'reward_type' => 'web',
            'days_to_expire' => 1,
        ])->assertStatus(409);
    }

    public function testSetLoyaltyOffersWithMissingRequest()
    {
        $this->json('PUT', '/api/brand/1/offer', [])->assertStatus(422);
    }

    public function testDeleteLoyaltyOffers()
    {
        $hotel = factory(Hotel::class)->create();

        $offer = factory(Offer::class)->create([
            'id_hotel' => $hotel->id,
            'id_cadena' => 0
        ]);

        $offerGoal = factory(OfferGoal::class)->create([
            'brand_id' => $hotel->brand_id,
            'offer_id' => $offer->id
        ]);

        $this->json('DELETE', '/api/brand/' . $hotel->brand_id . '/offer/' . $offerGoal->id, [])
            ->assertStatus(204);

        $this->assertEquals($offerGoal->fresh(), null);
    }

    public function testDeleteOffers()
    {
        $hotel = factory(Hotel::class)->create();

        $offer = factory(Offer::class)->create([
            'id_hotel' => $hotel->id,
            'id_cadena' => 0
        ]);

        $this->json('DELETE', '/api/brand/' . $hotel->brand_id . '/reward-offer/' . $offer->id, [])
            ->assertStatus(204);
    }

    public function testOfferDeleteNotAssignedToHotel()
    {
        $hotel1 = factory(Hotel::class)->create();

        $hotel2 = factory(Hotel::class)->create();

        $offer = factory(Offer::class)->create([
            'id_hotel'  => $hotel2->id,
            'id_cadena' => 0
        ]);

        $this->json('DELETE', '/api/brand/' . $hotel1->brand_id . '/reward-offer/' . $offer->id, [])
            ->assertStatus(500)
            ->assertJson([
                'errors' => 'Denied access to Resource to the current Brand'
            ]);
    }

    public function testOfferDoesNotExist()
    {
        $hotel = factory(Hotel::class)->create();

        $this->json('DELETE', '/api/brand/' . $hotel->brand_id . '/reward-offer/' . 0, [])
            ->assertStatus(404);
    }
}
