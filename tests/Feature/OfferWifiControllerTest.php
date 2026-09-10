<?php

namespace Tests\Feature;

use App\Hotel;
use App\Offer;
use Tests\TestCase;
use App\BrandOfferWifi;
use App\Exceptions\OfferWifi\OfferWifiInvalidRequestException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OfferWifiControllerTest extends TestCase
{
    public function testCreateOfferWifi()
    {
        $hotel = factory(Hotel::class)->create();
        $offer = factory(Offer::class)->create();
        $validFrom = now()->format('Y-m-d');
        $validTo = now()->addDays(20)->format('Y-m-d');
        $data[] = [
            'offer_id'          => $offer->id,
            'accommodated'      => true,
            'non_accommodated'  => false,
            'condition'         => 'always',
            'offer_type'        => 'web',
            'period'            => 20,
            'valid_from'        => $validFrom,
            'valid_to'          => $validTo,
            'is_default'        => false
        ];

        $check = $this->json('POST', "/api/brands/{$hotel->brand->id}/offers-wifi", $data)
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    0 => [
                        'condition'         => 'always',
                        'accommodated'      => true,
                        'non_accommodated'  => false,
                        'offer_type'        => 'web',
                        'period'            => 20,
                        'valid_from'        => $validFrom,
                        'valid_to'          => $validTo,
                        'is_default'        => false
                    ]
                ]
            ]);

        $id = $check->json()['data'][0]['id'];
        BrandOfferWifi::findOrFail($id);
    }

    public function testUpdateOfferWifi()
    {
        $validFrom = now()->toDateString();
        $validTo = now()->addDays(20)->toDateString();
        $hotel = factory(Hotel::class)->create();
        $offer = factory(Offer::class)->create();
        $brandOfferWifi = factory(BrandOfferWifi::class)->create([
            'brand_id'   => $hotel->brand->id,
            'offer_id'   => $offer->id,
            'accommodated'      => true,
            'non_accommodated'  => false,
            'condition'  => 'always',
            'offer_type' => 'web',
            'period'     => 20,
            'valid_from' => $validFrom,
            'valid_to'   => $validTo,
            'is_default' => false
        ]);

        $data[] = [
            'id'                => $brandOfferWifi->id,
            'offer_id'          => $offer->id,
            'accommodated'      => false,
            'non_accommodated'  => true,
            'condition'         => 'facebook_share',
            'offer_type'        => 'inmediate',
            'period'            => 30,
            'valid_from'        => $validFrom,
            'valid_to'          => $validTo,
            'is_default'        => true
        ];

        $this->json('POST', "/api/brands/{$hotel->brand->id}/offers-wifi", $data)
            ->assertStatus(204);

        $updatedBrandOfferWifi = BrandOfferWifi::findOrFail($brandOfferWifi->id);

        $this->assertEquals(30, $updatedBrandOfferWifi->period);
        $this->assertEquals('inmediate', $updatedBrandOfferWifi->offer_type);
        $this->assertEquals('facebook_share', $updatedBrandOfferWifi->condition);
    }

    public function testGetOfferWifi()
    {
        $validFrom = now()->format('Y-m-d');
        $validTo = now()->addDays(20)->format('Y-m-d');
        $hotel = factory(Hotel::class)->create();
        $offer = factory(Offer::class)->create();
        $brandOfferWifi = factory(BrandOfferWifi::class)->create([
            'brand_id'   => $hotel->brand->id,
            'offer_id'   => $offer->id,
            'condition'  => 'always',
            'offer_type' => 'web',
            'period'     => 20,
            'valid_from' => $validFrom,
            'valid_to'   => $validTo,
            'is_default' => false
        ]);

        $this->json('GET', "/api/brands/{$hotel->brand->id}/offers-wifi")
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    0 => [
                        'id'         => $brandOfferWifi->id,
                        'condition'  => 'always',
                        'offer_type' => 'web',
                        'period'     => 20,
                        'valid_from' => $validFrom,
                        'valid_to'   => $validTo,
                        'is_default' => false
                    ]
                ]
            ]);

        $checkValidFrom = now()->addDays(5)->toDateString();
        $checkValidTo = now()->addDays(15)->toDateString();

        $this->json('GET', "/api/brands/{$hotel->brand->id}/offers-wifi/en?valid_from={$checkValidFrom}&valid_to={$checkValidTo}")
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    0 => [
                        'id'         => $brandOfferWifi->id,
                        'condition'  => 'always',
                        'offer_type' => 'web',
                        'period'     => 20,
                        'valid_from' => $validFrom,
                        'valid_to'   => $validTo,
                        'is_default' => false
                    ]
                ]
            ]);

        //  Must fail
        $this->json('GET', "/api/brands/{$hotel->brand->id}/offers-wifi?valid_from=Saturday+night")
            ->assertStatus(400);
    }

    public function testDeleteOfferWifi()
    {
        $validFrom = now()->toDateString();
        $validTo = now()->addDays(20)->toDateString();
        $hotel = factory(Hotel::class)->create();
        $offer = factory(Offer::class)->create();
        $brandOfferWifi = factory(BrandOfferWifi::class)->create([
            'brand_id'   => $hotel->brand->id,
            'offer_id'   => $offer->id,
            'condition'  => 'always',
            'offer_type' => 'web',
            'period'     => 20,
            'valid_from' => $validFrom,
            'valid_to'   => $validTo,
            'is_default' => false
        ]);

        $this->json('DELETE', '/api/offers-wifi/' . $brandOfferWifi->id)
            ->assertStatus(200);
    }

    public function testDeleteOfferWifiNotExistingReturn404()
    {
        $this->json('DELETE', '/api/offers-wifi/1')->assertStatus(404);
    }
}
