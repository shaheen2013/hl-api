<?php

namespace Tests\Feature;

use App\Hotel;
use App\Cadena;
use App\CadenaHotel;
use App\Brand;
use App\HotelStaff;
use App\HotelStaffHotels;
use Carbon\Carbon;
use Tests\TestCase;

class StaffControllerTest extends TestCase
{
    public function testGetStaffOnIndependentHotelSuccessfully()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();

        $staff = factory(HotelStaff::class)->create([
            "deleted" => 0
        ]);

        $staffDeleted = factory(HotelStaff::class)->create([
            "deleted" => 1
        ]);

        factory(HotelStaffHotels::class)->create([
            "hotel_id"          => $hotel->id,
            "hotel_staff_id"    => $staff->id,
        ]);

        factory(HotelStaffHotels::class)->create([
            "hotel_id"          => $hotel->id,
            "hotel_staff_id"    => $staffDeleted->id,
        ]);

        // Test get staffs
        $this->json('GET', "/api/brands/$brand->id/staffs", ["brand_type" => "hotel"])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        'id' => $staff->id,
                        'name' => $staff->nombre,
                        'email' => $staff->email,
                        'hotels' => $hotel->hotelName,
                        'verified' => $staff->activo,
                        'role' => $staff->role->role_en
                    ],
                ]
            ]);
    }
    public function testGetStaffOnChainSuccessfully()
    {
        $chain = factory(Cadena::class)->create();
        $hotel1 = factory(Hotel::class)->create();
        $hotel2 = factory(Hotel::class)->create();
        factory(CadenaHotel::class)->create(
            [
                'id_cadena' => $chain->id,
                'id_hotel'  => $hotel1->id
            ]
        );
        factory(CadenaHotel::class)->create(
            [
                'id_cadena' => $chain->id,
                'id_hotel'  => $hotel2->id
            ]
        );

        $brand = Brand::where('hotel_id', '=', $hotel1->id)->first();

        $staff = factory(HotelStaff::class)->create([
            "deleted" => 0
        ]);

        $staffDeleted = factory(HotelStaff::class)->create([
            "deleted" => 1
        ]);

        factory(HotelStaffHotels::class)->create([
            "hotel_id"          => $hotel1->id,
            "hotel_staff_id"    => $staff->id,
        ]);

        factory(HotelStaffHotels::class)->create([
            "hotel_id"          => $hotel2->id,
            "hotel_staff_id"    => $staff->id,
        ]);

        factory(HotelStaffHotels::class)->create([
            "hotel_id"          => $hotel1->id,
            "hotel_staff_id"    => $staffDeleted->id,
        ]);

        // Test get staffs
        $this->json('GET', "/api/brands/$brand->parent_id/staffs", ["brand_type" => "chain"])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        'id' => $staff->id,
                        'name' => $staff->nombre,
                        'email' => $staff->email,
                        'hotels' => $hotel1->hotelName . "," . $hotel2->hotelName,
                        'verified' => $staff->activo,
                        'role' => $staff->role->role_en
                    ],
                ]
            ]);
    }
    public function testGetStaffWithBadParams()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();

        // Test get staffs
        $this->json('GET', "/api/brands/$brand->id/staffs")->assertStatus(400);
    }

    public function testDeleteStaffOnIndependentHotelSuccessfully()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();

        $staff = factory(HotelStaff::class)->create([
            "deleted" => 0
        ]);

        factory(HotelStaffHotels::class)->create([
            "hotel_id"          => $hotel->id,
            "hotel_staff_id"    => $staff->id,
        ]);

        // Test get staffs
        $this->json('DELETE', "/api/brands/$brand->id/staffs/$staff->id", ["brand_type" => "hotel"])
            ->assertStatus(204);

        $this->assertEquals($staff->fresh()->deleted, 1);
    }

    public function testDeleteStaffOnChainSuccessfully()
    {
        $chain = factory(Cadena::class)->create();
        $hotel1 = factory(Hotel::class)->create();
        $hotel2 = factory(Hotel::class)->create();
        factory(CadenaHotel::class)->create(
            [
                'id_cadena' => $chain->id,
                'id_hotel'  => $hotel1->id
            ]
        );
        factory(CadenaHotel::class)->create(
            [
                'id_cadena' => $chain->id,
                'id_hotel'  => $hotel2->id
            ]
        );

        $brand = Brand::where('hotel_id', '=', $hotel1->id)->first();
        $staff = factory(HotelStaff::class)->create([
            "deleted" => 0
        ]);

        factory(HotelStaffHotels::class)->create([
            "hotel_id"          => $hotel1->id,
            "hotel_staff_id"    => $staff->id,
        ]);

        factory(HotelStaffHotels::class)->create([
            "hotel_id"          => $hotel2->id,
            "hotel_staff_id"    => $staff->id,
        ]);

        // Test get staffs
        $this->json('DELETE', "/api/brands/$brand->parent_id/staffs/$staff->id", ["brand_type" => "chain"])
            ->assertStatus(204);


        $this->assertEquals($staff->fresh()->deleted, 1);
    }

    public function testDeleteStaffFromAnotherBrand()
    {
        $hotel = factory(Hotel::class)->create();
        $hotel2 = factory(Hotel::class)->create();
        $badBrand = Brand::where('hotel_id', '=', $hotel2->id)->first();
        $staff = factory(HotelStaff::class)->create();

        factory(HotelStaffHotels::class)->create([
            "hotel_id"          => $hotel->id,
            "hotel_staff_id"    => $staff->id,
        ]);

        // Test get staffs
        $this->json('DELETE', "/api/brands/$badBrand->id/staffs/$staff->id", ["brand_type" => "hotel"])
            ->assertStatus(400);
    }

    public function testDeleteStaffWithBadParams()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $staff = factory(HotelStaff::class)->create();

        // Test get staffs
        $this->json('DELETE', "/api/brands/$brand->id/staffs/$staff->id")->assertStatus(400);
    }
}
