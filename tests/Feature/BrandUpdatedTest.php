<?php

namespace Tests\Feature;

use App\Brand;
use App\Events\Brand\BrandUpdated;
use App\Exceptions\InvalidRequestException;
use App\Hotel;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Exception;

class BrandUpdatedTest extends TestCase
{
    public function mountBrandUpdatedPayload(int $id): array
    {
        $payload = [
            "brand" => [
                "id" => $id,
                "name" => "Test FULL payload name",
                "logo" => "Test logo",
                "email" => "ben.schowalter@example.net",
                "background_color" => "#cccccc",
                "fotoBg" => "Test background_image",
                "place_country" => "Test place_country",
                "time_zone" => "Europe/Berlin",
                "products" => [
                    [
                        "id" => 10,
                        "product_id" => 4,
                        "active" => 1,
                        "name" => "review",
                        "active_by_name" => [
                            "review" => 1
                        ]
                    ]
                ]
            ]
        ];
        return $payload;
    }

    public function testBrandUpdatesCorrectly()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', $hotel->id)->first();
        $payload = $this->mountBrandUpdatedPayload($brand->id);

        // Sometimes we have fields that are not set, so they are nullable
        $this->json('PUT', "/api/brands/" . $brand->id . "/info", [
            "brand" => [
                "id" => $brand->id,
                "name" => "Test name",
                "logo" => null,
                "email" => "ben.schowalter@example.net",
                "background_color" => null,
                "fotoBg" => "Test background_image",
                "place_country" => "Test place_country",
                "time_zone" => "Europe/Berlin",
                "products" => []
            ]
        ])->assertStatus(200);

        $this->assertDatabaseHas('hoteles', [
            "name" => "Test name",
            "logo" => "",
            "email" => "ben.schowalter@example.net",
            "place_country" => "Test place_country",
            "fotoBg" => "Test background_image",
        ]);

        $this->assertDatabaseHas('brands', [
            "background_color" => null,
        ]);

        // Full payload
        $this->json('PUT', "/api/brands/" . $brand->id . "/info", $payload)->assertStatus(200);

        $this->assertDatabaseHas('hoteles', [
            "name" => "Test FULL payload name",
            "logo" => "Test logo",
            "email" => "ben.schowalter@example.net",
            "fotoBg" => "Test background_image",
            "place_country" => "Test place_country",
        ]);

        $this->assertDatabaseHas('brands', [
            "background_color" => "#cccccc",
        ]);
    }

    public function testBrandUpdatedEventIsEmitted()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', $hotel->id)->first();
        $payload = $this->mountBrandUpdatedPayload($brand->id);

        Event::fake();

        // Update background_color
        $this->json('PUT', "/api/brands/" . $brand->id . "/info", [
            "brand" => [
                "id" => $brand->id,
                "background_color" => "#ababab",
            ]
        ])->assertStatus(200);

        Event::assertDispatched(BrandUpdated::class);

        // Full payload
        $this->json('PUT', "/api/brands/" . $brand->id . "/info", $payload)->assertStatus(200);
        Event::assertDispatched(BrandUpdated::class);
    }

    public function testInvalidPayload()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', $hotel->id)->first();

        Event::fake();

        try {
            $this->json('PUT', "/api/brands/" . $brand->id . "/info", [
                "brand" => [
                    "id" => $brand->id,
                    "name" => "I have INVALID background_color",
                    "background_color" => 1
                ]
            ])->assertStatus(400);
        } catch (Exception $e) {
            $this->expectException(InvalidRequestException::class);
        }

        Event::assertNotDispatched(BrandUpdated::class);
    }
}
