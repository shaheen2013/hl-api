<?php

namespace Tests\Feature;

use App\Hotel;
use App\HotelGuid;
use App\Brand;
use Tests\TestCase;

class BouncerControllerTest extends TestCase
{
    private $brand;

    public function setUp(): void
    {
        parent::setUp();
        $hotel = factory(Hotel::class)->create();
        factory(HotelGuid::class)->create(['id_hotel' => $hotel->id]);
        $this->brand = Brand::where('hotel_id', '=', $hotel->id)->first();
    }

    public function testGetFallbackBackgroundColor()
    {
        $this->brand->background_color =  null;
        $this->brand->save();
        $brandId = $this->brand->id;

        $this->get("/${brandId}")
            ->assertSee("#715aff");
    }

    public function testGetCorrectBackgroundColor()
    {
        $backgroundColor =  "#ebeb34";
        $this->brand->background_color =  $backgroundColor;
        $this->brand->save();
        $brandId = $this->brand->id;

        $this->get("/${brandId}")
            ->assertSee($backgroundColor);
    }

    public function test404WhenNotPassNumber()
    {
        $this->get("/TEST")
            ->assertStatus(404);
        $this->get(".env")
            ->assertStatus(404);
    }

    public function testErrorWhenNotFoundBrandId()
    {
        $this->get("/0")
            ->assertStatus(404);
    }
}
