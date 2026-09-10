<?php

namespace Tests\Feature;

use App\Brand;
use App\Cadena;
use App\Hotel;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Tests\TestCase;

class GdprControllerTest extends TestCase
{
    // GET ENDPOINT
    /** @test */
    public function theGdprJsonStructureIsCorrect()
    {
        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', $hotel->id)
            ->first();

        $language = 'en';

        $this->json('GET', "/api/brands/$brand->id/gdpr", ['lang' => $language])
            ->assertJsonStructure([
                'data' => [
                    'restricted_portal',
                    'first_eprivacy_page',
                    'second_eprivacy_page',
                    'legal_text'
                ]
            ]);
    }

    /** @test */
    public function theGdprForChainThrowAnError()
    {
        $chain = factory(Cadena::class)->create();

        $brand = Brand::where('chain_id', $chain->id)
            ->first();

        $language = 'en';

        $this->json('GET', "/api/brands/$brand->id/gdpr", ['lang' => $language])
            ->assertStatus(400)
            ->assertJsonStructure([
                'errors'
            ]);
    }

    /** @test */
    public function theGdprHasNoPlaceholders()
    {
        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', $hotel->id)
            ->first();

        $language = 'en';

        $placeholders = [
            '{{brandLegalName}}',
            '{{brandLegalAddress}}',
            '{{brandNIF}}',
            '{{brandLegalEmail}}',
        ];

        $gdpr = $this->json('GET', "/api/brands/$brand->id/gdpr", ['lang' => $language])
            ->assertStatus(200);

        $containsPlaceholder = false;

        foreach ($placeholders as $placeholder) {
            foreach ($gdpr as $gdprSection) {
                $containsPlaceholder = Str::contains($gdprSection, $placeholder);
            }
        }

        $this->assertFalse($containsPlaceholder);
    }

    // POST ENDPOINT

    public function testGdprPayloadIsIncomplete()
    {

        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', $hotel->id)
            ->first();

        $body = [
            "hotelId" => $hotel->id,
            "chainId" => null
        ];

        try {
            $this->json('POST', "/api/brands/$brand->id/gdpr", $body, ['contentType' => 'application/json'])->assertStatus(400);
        } catch (Exception $e) {
            $this->expectException(ValidationException::class);
        }
    }

    public function testGdprUpdatesBrandCustomVars()
    {

        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', $hotel->id)
            ->first();

        $body = [
            "hotelId" => $hotel->id,
            "chainId" => null,
            "info" => [
                "customVars" => [
                    "brandLegalName" => "Test",
                    "brandLegalAddress" => "testAddress",
                    "brandNIF" => "123456789A",
                    "brandLegalEmail" => "test@test.com"
                ],
                "companyData" => [
                    "companyName" => "Test",
                    "companyAddress" => "testAddress",
                    "companyNif" => "123456789A",
                    "companyEmail" => "test@test.com",
                    "restrictedPortal" => 0
                ],
            ]
        ];



        $this->json('POST', "/api/brands/$hotel->id/gdpr", $body, ['contentType' => 'application/json'])->assertStatus(204);
        $this->assertDatabaseHas('brand_eprivacy', ['hotel_id' => $hotel->id, 'chain_id' => null, 'company_name' => 'Test', 'company_address' => 'testAddress', 'company_nif' => '123456789A', 'company_email' => 'test@test.com', 'restricted_portal' => 0]);
        foreach ($body['info']['customVars'] as $customVar) {
            $this->assertDatabaseHas('brand_custom_vars', ['hotel_id' => $hotel->id, 'chain_id' => null, 'value' => $customVar]);
        }
    }
    public function testGdprUpdatesEprivacyTexts()
    {

        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', $hotel->id)
            ->first();
        $body = [
            "hotelId" => $hotel->id,
            "chainId" => null,
            "info" => [
                "customVars" => [
                    "brandLegalName" => "Test",
                    "brandLegalAddress" => "testAddress",
                    "brandNIF" => "123456789A",
                    "brandLegalEmail" => "test@test.com"
                ],
                "companyData" => [
                    "companyName" => "Test",
                    "companyAddress" => "testAddress",
                    "companyNif" => "123456789A",
                    "companyEmail" => "test@test.com",
                    "restrictedPortal" => 0
                ],
                "customContent" => [
                    [
                        "configuration" => "custom_content",
                        "restrictedPortal" => "classic",
                        "pageName" => "first_eprivacy_page"
                    ],
                    [
                        "configuration" => "custom_content",
                        "restrictedPortal" => "classic",
                        "pageName" => "second_eprivacy_page"
                    ],
                    [
                        "configuration" => "custom_content",
                        "restrictedPortal" => "classic",
                        "pageName" => "second_eprivacy_page"
                    ],
                    [
                        "configuration" => "custom_content",
                        "restrictedPortal" => "classic",
                        "pageName" => "legal_text"
                    ]
                ],
                "customTexts" => [
                    [
                        "content" => [
                            "ca" => "catalan",
                            "de" => "aleman",
                            "en" => "ingles",
                            "es" => "castellano",
                            "fr" => "frances",
                            "it" => "italiano",
                            "zh" => "chino",
                            "pt" => "portugués",
                            "bg" => "bulgaro",
                            "ru" => "ruso"
                        ],
                        "active" => 1,
                        "configuration" => "custom_content",
                        "pageName" => "first_eprivacy_page",
                        "moduleName" => "eprivacy_text"
                    ],
                    [
                        "content" => [],
                        "active" => 0,
                        "configuration" => "custom_content",
                        "pageName" => "second_eprivacy_page",
                        "moduleName" => "second_eprivacy_text"
                    ],
                    [
                        "content" => [],
                        "active" => 0,
                        "configuration" => "custom_content",
                        "pageName" => "second_eprivacy_page",
                        "moduleName" => "second_eprivacy_not_client_text"
                    ],
                    [
                        "content" => [],
                        "active" => 0,
                        "configuration" => "custom_content",
                        "pageName" => "legal_text",
                        "moduleName" => "legal_text"
                    ]
                ]
            ]
        ];
        $this->json('POST', "/api/brands/$hotel->id/gdpr", $body, ['contentType' => 'application/json'])->assertStatus(204);
        $this->assertDatabaseHas('brand_custom_content', ['hotel_id' => $hotel->id, 'chain_id' => null, 'custom_content_id' => 1, 'active' => 1, 'custom_content_state_id' => 1, 'configuration' => 'custom_content']);
        foreach ($body['info']['customTexts'][0]['content'] as $customText) {
            $this->assertDatabaseHas('brand_custom_module_content', ['custom_module_id' => 1, 'content' => $customText, 'active' => 1]);
        }
    }
    public function testGdprThrowsExceptionIfAllLangTextsAreNotFilled()
    {
        $this->withoutExceptionHandling();
        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', $hotel->id)
            ->first();

        $body = [
            "hotelId" => $hotel->id,
            "chainId" => null,
            "info" => [
                "customVars" => [
                    "brandLegalName" => "Test",
                    "brandLegalAddress" => "testAddress",
                    "brandNIF" => "123456789A",
                    "brandLegalEmail" => "test@test.com"
                ],
                "companyData" => [
                    "companyName" => "Test",
                    "companyAddress" => "testAddress",
                    "companyNif" => "123456789A",
                    "companyEmail" => "test@test.com",
                    "restrictedPortal" => 0
                ],
                "customContent" => [
                    [
                        "configuration" => "custom_content",
                        "restrictedPortal" => "classic",
                        "pageName" => "first_eprivacy_page"
                    ],
                    [
                        "configuration" => "custom_content",
                        "restrictedPortal" => "classic",
                        "pageName" => "second_eprivacy_page"
                    ],
                    [
                        "configuration" => "custom_content",
                        "restrictedPortal" => "classic",
                        "pageName" => "second_eprivacy_page"
                    ],
                    [
                        "configuration" => "custom_content",
                        "restrictedPortal" => "classic",
                        "pageName" => "legal_text"
                    ]
                ],
                "customTexts" => [
                    [
                        "content" => [
                            "ca" => "catalan",
                            "de" => "aleman",
                            "en" => "ingles",
                            "es" => "castellano",
                            "fr" => "frances",
                            "it" => "italiano",
                        ],
                        "active" => 1,
                        "configuration" => "custom_content",
                        "pageName" => "first_eprivacy_page",
                        "moduleName" => "eprivacy_text"
                    ],
                    [
                        "content" => [],
                        "active" => 0,
                        "configuration" => "custom_content",
                        "pageName" => "second_eprivacy_page",
                        "moduleName" => "second_eprivacy_text"
                    ],
                    [
                        "content" => [],
                        "active" => 0,
                        "configuration" => "custom_content",
                        "pageName" => "second_eprivacy_page",
                        "moduleName" => "second_eprivacy_not_client_text"
                    ],
                    [
                        "content" => [],
                        "active" => 0,
                        "configuration" => "custom_content",
                        "pageName" => "legal_text",
                        "moduleName" => "legal_text"
                    ]
                ]
            ]
        ];

        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("SetBrandCustomModuleContentError");

        $this->json('POST', "/api/brands/$brand->id/gdpr", $body, ['contentType' => 'application/json'])
            ->assertStatus(400);
    }

    public function createGdprEventsPayload(int $user_id, int $hotel_id): array
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $payload = [
            'user_id' => $user_id,
            'hotel_id' => $hotel_id,
            'gdpr_events' => [
                ["event" => "client", "created_at" => $now],
                ["event" => "notifications", "created_at" => $now],
                ["event" => "conditions", "created_at" => $now]
            ]
        ];
        return $payload;
    }

    public function testGdprEventsAreInsertedWhenUserIsCreated()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', $hotel->id)->first();
        $user = factory(User::class)->create();

        $payload = $this->createGdprEventsPayload($user->id, $hotel->id);

        $this->json('POST', "/api/brands/$brand->id/gdpr/events", ["data" => $payload])
            ->assertStatus(201);

        $this->assertDatabaseHas('gdpr_history', [
            "hotel_id"   => $hotel->id,
            "user_id"    => $user->id,
            "event"      => $payload['gdpr_events'][0]['event'],
            "created_at" => $payload['gdpr_events'][0]['created_at']
        ]);

        $this->assertDatabaseHas('gdpr_history', [
            "hotel_id"   => $hotel->id,
            "user_id"    => $user->id,
            "event"      => $payload['gdpr_events'][1]['event'],
            "created_at" => $payload['gdpr_events'][1]['created_at']
        ]);

        $this->assertDatabaseHas('gdpr_history', [
            "hotel_id"   => $hotel->id,
            "user_id"    => $user->id,
            "event"      => $payload['gdpr_events'][2]['event'],
            "created_at" => $payload['gdpr_events'][2]['created_at']
        ]);
    }
}
