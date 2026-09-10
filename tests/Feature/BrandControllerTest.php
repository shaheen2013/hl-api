<?php

namespace Tests\Feature;

use App\Brand;
use App\Cadena;
use App\CadenaHotel;
use App\Hotel;
use App\BrandProtocol;
use App\User;
use App\UserBrand;
use App\Visit;
use App\Satisfaction;
use Tests\TestCase;
use Carbon\Carbon;

class BrandControllerTest extends TestCase
{
    public function testHotelsAreShowCorrectly()
    {
        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();

        $this->json('GET', "/api/brands/$brand->id")
            ->assertStatus(200)
            ->assertJson([
                'name' => $hotel->hotelName
            ]);
    }

    public function testBrandChilds()
    {
        $chain = factory(Cadena::class)->create();
        $hotel = factory(Hotel::class)->create();
        factory(CadenaHotel::class)->create(
            [
                'id_cadena' => $chain->id,
                'id_hotel'  => $hotel->id
            ]
        );

        $brand = Brand::where('chain_id', $chain->id)->first();
        $hotelBrand = Brand::where('hotel_id', $hotel->id)->first();

        $userInHours = factory(User::class)->create();
        $userNotInHours = factory(User::class)->create();

        $satisfaction = factory(Satisfaction::class)->create([
            'id_hotel'      => $hotel->id,
            'id_usuario'    => $userNotInHours->id,
            'done'          => 1
        ]);

        $userBrandInHours = factory(UserBrand::class)->create([
            'user_id' => $userInHours->id,
            'brand_id' =>  $hotelBrand->id,
            'date' => Carbon::now()->subHours(6)
        ]);
        $userBrandNotInHours = factory(UserBrand::class)->create([
            'user_id' => $userNotInHours->id,
            'brand_id' =>  $hotelBrand->id,
            'date' => Carbon::now()->subHours(48)
        ]);

        factory(Visit::class)->create([
            'user_brand_id' => $userBrandInHours->id,
            'check_in'      => Carbon::now()->subHours(6),
            'check_out'     => Carbon::now()->subHours(6)->addDays(7),
            'brand_id'      => $userBrandInHours->brand_id,
            'user_id'       => $userBrandInHours->user_id
        ]);
        factory(Visit::class)->create([
            'user_brand_id' => $userBrandNotInHours->id,
            'check_in'      => Carbon::now()->subHours(48),
            'check_out'     => Carbon::now()->subHours(48)->addDays(7),
            'brand_id'      => $userBrandNotInHours->brand_id,
            'user_id'       => $userBrandNotInHours->user_id
        ]);


        $this->json('GET', "/api/brands/{$brand->id}/childs/hotel?score=1&users=1")
            ->assertStatus(200)
            ->assertJson([
                'name' => $chain->nombre,
                'child_data' =>
                    [
                        0 => [
                            'users' => 1,
                            'score' => $satisfaction->puntuacion,
                        ]
                    ]
            ])
            ->assertJsonStructure(
                [
                    'id',
                    'chain_id',
                    'name',
                    'logo',
                    'childs',
                    'child_data' =>
                        [
                            0 => [
                                'id',
                                'name',
                                'place',
                                'logo',
                                'lang',
                                'users',
                                'score',
                                'last_register',
                                'last_register_days',
                                'last_registers'
                            ]
                        ]
                ]
            );
    }

    public function testBrandProtocol()
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

        $this->json('GET', "/api/brands/{$brand->id}/protocols")
            ->assertStatus(200)
            ->assertJson([
                0 => [
                    'id'       => $protocol->id,
                    'brand_id' => $brand->id,
                    'service'  => $protocol->service
                ]
            ]);

        $this->json('GET', "/api/brands/{$brand->id}/protocols/{$protocol->service}")
            ->assertStatus(200)
            ->assertJson([
                'id'       => $protocol->id,
                'brand_id' => $brand->id,
                'service'  => $protocol->service
            ]);
    }

    public function testBrandInfo()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = $hotel->brand;
        $brand->background_color = '#F0F0F0';
        $brand->save();

        $this->json('GET', "/api/brands/{$brand->id}/info")
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id'               => $brand->id,
                    'name'             => $hotel->name,
                    'logo'             => $hotel->logo,
                    'background_image' => $hotel->fotoBg,
                    'background_color' => $brand->background_color,
                    'email'            => $hotel->email,
                    'street'           => $hotel->street,
                    'city'             => $hotel->city,
                    'place_name'       => $hotel->place_name,
                    'place_country'    => $hotel->place_country,
                    'place_adm_area'   => $hotel->place_adm_area,
                    'stars'            => $hotel->estrellas,
                    'sending_email'    => $hotel->sending_email,
                    'stay_time'        => $hotel->stay_time
                ]
            ]);
    }
}
