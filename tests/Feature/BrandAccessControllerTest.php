<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Hotel;
use App\Cadena;
use App\CadenaHotel;
use App\BrandAccess;
use App\BrandAccessType;

class BrandAccessControllerTest extends TestCase
{
    public function testGetBrandAccessCode()
    {
        $hotel = factory(Hotel::class)->create();
        $brandAccess = factory(BrandAccess::class)->create(["brand_id" => $hotel->brand->id]);

        $this->json('GET', "/api/brands/{$hotel->brand->id}/access/")
            ->assertStatus(200)
            ->assertJson([
                "codes" => json_decode($brandAccess->codes, true)
            ]);
    }

    /*
        If all codes are requested and we have configured codes at the parent level,
        we return the codes of the selected brand with the premium codes overwritten
        by those of the parent
    */
    public function testGetBrandAccessCodeWithParentPremiumCodes()
    {
        $hotel = factory(Hotel::class)->create();
        $chain = $this->attachHotelToChain($hotel->id);

        $brandAccess = factory(BrandAccess::class)->create(["brand_id" => $hotel->brand->id]);
        $brandParentAccess = $this->prepareParentBrandAccess($chain->brand->id);
        ;

        $codesResult = array_merge(json_decode($brandAccess->codes, true), json_decode($brandParentAccess->codes, true));

        $this->json('GET', "/api/brands/{$hotel->brand->id}/access/")
            ->assertStatus(200)
            ->assertJson([
                "codes" => $codesResult
            ]);
    }

    public function testGetBrandAccessCodeByType()
    {
        $hotel = factory(Hotel::class)->create();
        $brandAccess = factory(BrandAccess::class)->create(["brand_id" => $hotel->brand->id]);

        $this->json('GET', "/api/brands/{$hotel->brand->id}/access/?type=room")
            ->assertStatus(200)
            ->assertJson([
                "codes" => json_decode($brandAccess->codes)->room->codes
            ]);
    }

    /*
        If request from premium codes and we have a premium codes configuration by parent
        we return only the parent codes
    */
    public function testGetPremiumBrandAccessCodesWithParent()
    {
        $hotel = factory(Hotel::class)->create();
        $chain = $this->attachHotelToChain($hotel->id);

        $brandAccess = factory(BrandAccess::class)->create(["brand_id" => $hotel->brand->id]);
        $brandParentAccess = $this->prepareParentBrandAccess($chain->brand->id);
        ;

        $this->json('GET', "/api/brands/{$hotel->brand->id}/access/?type=premium")
            ->assertStatus(200)
            ->assertJson([
                "codes" => json_decode($brandParentAccess->codes)->premium->codes
            ]);
    }

    /*
        If request from other codes and we have a premium codes configuration by parent
        we return the brand requested codes
    */
    public function testGetOtherBrandAccessCodesWithParent()
    {
        $hotel = factory(Hotel::class)->create();
        $chain = $this->attachHotelToChain($hotel->id);

        $brandAccess = factory(BrandAccess::class)->create(["brand_id" => $hotel->brand->id]);
        $brandParentAccess = $this->prepareParentBrandAccess($chain->brand->id);
        ;

        $this->json('GET', "/api/brands/{$hotel->brand->id}/access/?type=room")
            ->assertStatus(200)
            ->assertJson([
                "codes" => json_decode($brandAccess->codes)->room->codes
            ]);
    }

    public function testCreateBrandAccessCodes()
    {
        $hotel = factory(Hotel::class)->create();

        $this->json('PUT', '/api/brands/' . $hotel->brand->id . '/access', [
            'type' => 'room',
            'codes' => [
                "codes" => ["test", "codes"]
            ]
        ])->assertStatus(204);

        $this->json('GET', "/api/brands/{$hotel->brand->id}/access/")
            ->assertStatus(200)
            ->assertJson([
                "codes" => [
                    "room" => [
                        "codes" => ["test", "codes"]
                    ]
                ]
            ]);
    }

    public function testUpdateBrandAccessCodes()
    {
        $hotel = factory(Hotel::class)->create();
        $brandAccess = factory(BrandAccess::class)->create(["brand_id" => $hotel->brand->id]);

        $this->json('PUT', '/api/brands/' . $hotel->brand->id . '/access', [
            'type' => 'room',
            'codes' => [
                "codes" => ["test", "codes"]
            ]
        ])->assertStatus(204);

        $this->json('GET', "/api/brands/{$hotel->brand->id}/access/")
            ->assertStatus(200)
            ->assertJson([
                "codes" => [
                    "room" => [
                        "codes" => ["test", "codes"]
                    ],
                    "guest" => [
                        "codes" => json_decode($brandAccess->codes)->guest->codes
                    ],
                    "premium" => [
                        "codes" => json_decode($brandAccess->codes)->premium->codes
                    ]
                ]
            ]);
    }

    public function testCreatePremiumBrandAccessCodesWithParent()
    {
        $hotel = factory(Hotel::class)->create();
        $chain = $this->attachHotelToChain($hotel->id);
        $brandAccess = factory(BrandAccess::class)->create(["brand_id" => $hotel->brand->id]);

        $this->json('PUT', '/api/brands/' . $hotel->brand->id . '/access', [
            'type' => 'premium',
            'codes' => [
                "codes" => ["test", "codes"],
                "byParent" => true
            ]
        ])->assertStatus(204);

        $this->json('GET', "/api/brands/{$hotel->brand->id}/access/")
            ->assertStatus(200)
            ->assertJson([
                "codes" => [
                    "room" => [
                        "codes" => json_decode($brandAccess->codes)->room->codes
                    ],
                    "guest" => [
                        "codes" => json_decode($brandAccess->codes)->guest->codes
                    ],
                    "premium" => [
                        "codes" => ["test", "codes"],
                        "byParent" => true
                    ]
                ]
            ]);
    }

    public function testUpdatePremiumBrandAccessCodesWithParent()
    {
        $hotel = factory(Hotel::class)->create();
        $chain = $this->attachHotelToChain($hotel->id);
        $brandAccess = factory(BrandAccess::class)->create(["brand_id" => $hotel->brand->id]);
        $brandParentAccess = $this->prepareParentBrandAccess($chain->brand->id);
        ;

        $this->json('PUT', '/api/brands/' . $hotel->brand->id . '/access', [
            'type' => 'premium',
            'codes' => [
                "codes" => ["test", "codes"],
                "byParent" => true
            ]
        ])->assertStatus(204);

        $this->json('GET', "/api/brands/{$hotel->brand->id}/access/")
            ->assertStatus(200)
            ->assertJson([
                "codes" => [
                    "room" => [
                        "codes" => json_decode($brandAccess->codes)->room->codes
                    ],
                    "guest" => [
                        "codes" => json_decode($brandAccess->codes)->guest->codes
                    ],
                    "premium" => [
                        "codes" => ["test", "codes"],
                        "byParent" => true
                    ]
                ]
            ]);
    }

    public function testDeleteParentBrandAccessCodesWithoutParent()
    {
        $hotel = factory(Hotel::class)->create();
        $chain = $this->attachHotelToChain($hotel->id);
        $brandAccess = factory(BrandAccess::class)->create(["brand_id" => $hotel->brand->id]);
        $brandParentAccess = $this->prepareParentBrandAccess($chain->brand->id);
        ;

        $this->json('PUT', '/api/brands/' . $hotel->brand->id . '/access', [
            'type' => 'premium',
            'codes' => [
                "codes" => ["test", "codes"],
                "byParent" => false
            ]
        ])->assertStatus(204);

        $this->json('GET', "/api/brands/{$hotel->brand->id}/access/")
            ->assertStatus(200)
            ->assertJson([
                "codes" => [
                    "room" => [
                        "codes" => json_decode($brandAccess->codes)->room->codes
                    ],
                    "guest" => [
                        "codes" => json_decode($brandAccess->codes)->guest->codes
                    ],
                    "premium" => [
                        "codes" => ["test", "codes"],
                        "byParent" => false
                    ]
                ]
            ]);

        $this->assertEquals(BrandAccess::where(['brand_id' => $hotel->brand->parent_id])->first(), null);
    }

    public function testUpdateOtherBrandAccessCodesWithPremiumOnParent()
    {
        $hotel = factory(Hotel::class)->create();
        $chain = $this->attachHotelToChain($hotel->id);
        $brandAccess = factory(BrandAccess::class)->create(["brand_id" => $hotel->brand->id]);
        $brandParentAccess = $this->prepareParentBrandAccess($chain->brand->id);
        ;

        $this->json('PUT', '/api/brands/' . $hotel->brand->id . '/access', [
            'type' => 'room',
            'codes' => [
                "codes" => ["test", "codes"]
            ]
        ])->assertStatus(204);

        $this->json('GET', "/api/brands/{$hotel->brand->id}/access/")
            ->assertStatus(200)
            ->assertJson([
                "codes" => [
                    "room" => [
                        "codes" => ["test", "codes"]
                    ],
                    "guest" => [
                        "codes" => json_decode($brandAccess->codes)->guest->codes
                    ],
                    "premium" => [
                        "codes" => json_decode($brandParentAccess->codes)->premium->codes
                    ]
                ]
            ]);
    }

    private function prepareParentBrandAccess($brandID)
    {
        return factory(BrandAccess::class)->create([
            "brand_id" => $brandID,
            "codes" => json_encode([
                "premium" => [
                    "codes" => [1,2,3],
                    "byParent" => true
                ]
            ])
        ]);
    }

    private function attachHotelToChain($hotelID)
    {
        $chain = factory(Cadena::class)->create();
        factory(CadenaHotel::class)->create(
            [
                'id_cadena' => $chain->id,
                'id_hotel'  => $hotelID
            ]
        );

        return $chain;
    }

    public function testGetDefaultBrandAccessTypes()
    {
        $hotel = factory(Hotel::class)->create();

        $this->json('GET', "/api/brands/{$hotel->brand->id}/access_types")
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "name"      => "Form",
                        "active"    => 1,
                    ],
                    [
                        "name"      => "Facebook",
                        "active"    => 1,
                    ],
                ]
            ]);
    }

    public function testGetOverrideBrandAccessTypes()
    {
        $hotel = factory(Hotel::class)->create();
        factory(BrandAccessType::class)->create([
            "brand_id"          => $hotel->brand->id,
            "access_type_id"    => 1,
            "active"            => 0
        ]);
        factory(BrandAccessType::class)->create([
            "brand_id"          => $hotel->brand->id,
            "access_type_id"    => 2,
            "active"            => 0
        ]);

        $this->json('GET', "/api/brands/{$hotel->brand->id}/access_types")
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "name"      => "Form",
                        "active"    => 0,
                    ],
                    [
                        "name"      => "Facebook",
                        "active"    => 0,
                    ],
                ]
            ]);
    }

    public function testPutWhenNotExistsBrandAccessTypes()
    {
        $hotel = factory(Hotel::class)->create();

        $this->json('PUT', "/api/brands/{$hotel->brand->id}/access_types/1", [
            'active' => '1',
        ])->assertStatus(204);

        $brandAccessType = BrandAccessType::where([
            "brand_id" => $hotel->brand->id,
            "access_type_id" => 1
        ])->first();

        $this->assertNotEquals($brandAccessType, null);
        $this->assertEquals($brandAccessType->active, 1);
    }

    public function testPutWhenExistsBrandAccessTypes()
    {
        $hotel = factory(Hotel::class)->create();
        factory(BrandAccessType::class)->create([
            "brand_id"          => $hotel->brand->id,
            "access_type_id"    => 1,
            "active"            => 1
        ]);

        $this->json('PUT', "/api/brands/{$hotel->brand->id}/access_types/1", [
            'active' => '0',
        ])->assertStatus(204);

        $brandAccessType = BrandAccessType::where([
            "brand_id" => $hotel->brand->id,
            "access_type_id" => 1
        ])->first();

        $this->assertNotEquals($brandAccessType, null);
        $this->assertEquals($brandAccessType->active, 0);
    }

    public function testPutWWithInvalidData()
    {
        $hotel = factory(Hotel::class)->create();
        factory(BrandAccessType::class)->create([
            "brand_id"          => $hotel->brand->id,
            "access_type_id"    => 1,
            "active"            => 1
        ]);

        $this->json('PUT', "/api/brands/{$hotel->brand->id}/access_types/1", [])->assertStatus(400);
    }
}
