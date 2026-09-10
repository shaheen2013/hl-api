<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\BrandProtocol;
use App\Brand;
use App\Cadena;
use App\CadenaHotel;
use App\Hotel;
use Faker\Factory;

class ProtocolControllerTest extends TestCase
{
    public function testCreateBrandProtocol()
    {
        $faker = Factory::create();
        $chain = factory(Cadena::class)->create();
        $hotel = factory(Hotel::class)->create();
        factory(CadenaHotel::class)->create(
            [
                'id_cadena' => $chain->id,
                'id_hotel'  => $hotel->id
            ]
        );

        $brand = Brand::where('chain_id', $chain->id)
            ->first();

        $service = $faker->randomElement(['emails', 'portal']);
        $treatment = $faker->randomElement(['formal', 'informal']);

        $this->json('POST', "/api/protocols", [
            'brand_id'  => $brand->id,
            'service'   => $service,
            'treatment' => $treatment
        ])
            ->assertStatus(201)
            ->assertJson([
                'brand_id'  => $brand->id,
                'service'   => $service,
                'treatment' => $treatment
            ]);

        $this->json('POST', "/api/protocols", [
            'brand_id'  => $brand->id,
            'service'   => $service,
            'treatment' => $treatment
        ])
            ->assertStatus(400);
    }

    public function testUpdateBrandProtocol()
    {
        $chain = factory(Cadena::class)->create();
        $hotel = factory(Hotel::class)->create();
        factory(CadenaHotel::class)->create(
            [
                'id_cadena' => $chain->id,
                'id_hotel'  => $hotel->id
            ]
        );

        $brand = Brand::where('chain_id', $chain->id)
            ->first();

        $protocol = factory(BrandProtocol::class)->create([
            'brand_id'  => $brand->id,
            'treatment' => 'formal'
        ]);

        $this->json('PUT', "/api/protocols/{$protocol->id}", [
            'treatment' => 'informal'
        ])
            ->assertStatus(200)
            ->assertJson([
                'brand_id'  => $brand->id,
                'service'   => $protocol->service,
                'treatment' => 'informal'
            ]);
    }

    public function testShowBrandProtocol()
    {
        $chain = factory(Cadena::class)->create();
        $hotel = factory(Hotel::class)->create();
        factory(CadenaHotel::class)->create(
            [
                'id_cadena' => $chain->id,
                'id_hotel'  => $hotel->id
            ]
        );

        $brand = Brand::where('chain_id', $chain->id)
            ->first();

        $protocol = factory(BrandProtocol::class)->create(
            [
                'brand_id' => $brand->id
            ]
        );

        $this->json('GET', "/api/protocols/{$protocol->id}")
            ->assertStatus(200)
            ->assertJson([
                'brand_id'  => $brand->id,
                'service'   => $protocol->service,
                'treatment' => $protocol->treatment
            ]);
    }

    public function testDeleteBrandProtocol()
    {
        $chain = factory(Cadena::class)->create();
        $hotel = factory(Hotel::class)->create();
        factory(CadenaHotel::class)->create(
            [
                'id_cadena' => $chain->id,
                'id_hotel'  => $hotel->id
            ]
        );

        $brand = Brand::where('chain_id', $chain->id)
            ->first();

        $protocol = factory(BrandProtocol::class)->create(
            [
                'brand_id' => $brand->id
            ]
        );

        $this->json('DELETE', "/api/protocols/{$protocol->id}")
            ->assertStatus(204);
    }
}
