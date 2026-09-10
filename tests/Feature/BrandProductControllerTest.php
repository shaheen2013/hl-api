<?php

namespace Tests\Feature;

use App\User;
use App\Survey;
use App\SurveyQuestion;
use App\UserSurvey;
use App\UserSurveyQuestionAnswer;
use App\Satisfaction;
use App\Category;
use App\Question;
use App\Brand;
use App\BrandEprivacy;
use App\BrandProduct;
use App\Cadena;
use App\CadenaHotel;
use App\Hotel;
use App\HotelSatisfaction;
use App\ProductConfig;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Log;
use App\Events\Autocheckin\AutocheckinUpdated;
use App\Events\Brand\BrandUpdated;
use App\Product;
use Illuminate\Support\Facades\Event;

class BrandProductControllerTest extends TestCase
{
    use WithoutMiddleware;

    private $productService;
    private $brandProductService;

    public function setUp(): void
    {
        parent::setUp();

        Event::fake();

        $this->productService = $this->app->make('App\Services\Products\ProductService');
        $this->brandProductService = $this->app->make('App\Services\BrandProductService');
    }

    public function testStandardBrandPermissionsAreSetCorrectly()
    {
        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $product = $this->productService->getByName('satisfaction');

        $oldBrandProduct = factory(BrandProduct::class)->create([
            'brand_id'   => $brand->id,
            'product_id' => $product->id,
            'active'     => 0
        ]);

        if ($product) {
            $this->json('PUT', "/api/brands/$brand->id/products/$product->id/activate/1")
                ->assertStatus(204);
        }

        Event::assertDispatched(BrandUpdated::class);

        $newBrandProduct = $this->brandProductService->get($brand->id, $product->id);

        $this->assertNotEquals($oldBrandProduct->active, $newBrandProduct->active);
    }

    public function testPortalProBrandPermissionsAreSetCorrectly()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = $hotel->brand;
        $product = $this->productService->getByName('portal_pro');
        $oldBrandProduct = factory(BrandProduct::class)->create([
            'brand_id'   => $brand->id,
            'product_id' => $product->id,
            'active'     => 0
        ]);

        if ($product) {
            $this->json('PUT', "/api/brands/$brand->id/products/$product->id/activate/1")
                ->assertStatus(204);
        }

        Event::assertDispatched(BrandUpdated::class);

        $newBrandProduct = $this->brandProductService->get($brand->id, $product->id);
        $hotelBrandProducts = $this->brandProductService->getAllStatus($brand->id);

        $this->assertNotEquals($oldBrandProduct->active, $newBrandProduct->active);
       
        // TODO display_require_room es un config de producto. Hay que generar las relaciones nuevas de brand_product_config, etc. para poder hacer esta acción:
        // $this->assertEquals($hotelBrandProducts['display_require_room'], 0);
    }

    public function testLoyaltyBrandPermissionsAreSetCorrectly()
    {
        $hotel = factory(Hotel::class)->create();
        $hotelSatisfaction = factory(HotelSatisfaction::class)->create([
            'id_hotel'      => $hotel->id,
            'warning_email' => 'testSuccess'
        ]);

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $product = $this->productService->getByName('loyalty');

        $oldBrandProduct = factory(BrandProduct::class)->create([
            'brand_id'   => $brand->id,
            'product_id' => $product->id,
            'active'     => 0
        ]);

        if ($product) {
            $this->json('PUT', "/api/brands/$brand->id/products/$product->id/activate/1")
                ->assertStatus(204);
        }

        Event::assertDispatched(BrandUpdated::class);

        $newBrandProduct = $this->brandProductService->get($brand->id, $product->id);

        $this->assertNotEquals($oldBrandProduct->active, $newBrandProduct->active);
        $this->assertEquals($brand->fresh()->hotel->loyalty_emails, 'testSuccess');
    }

    public function testSastisfactionBrandPermissionsAreSetCorrectly()
    {
        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $product = $this->productService->getByName('satisfaction');

        $this->assertEquals($brand->hotel->hotelSatisfaction, null);
        $this->assertEquals($brand->hotel->hotelReview, null);

        if ($product) {
            $this->json('PUT', "/api/brands/$brand->id/products/$product->id/activate/1")
                ->assertStatus(204);
        }

        Event::assertDispatched(BrandUpdated::class);

        $this->assertNotEquals($brand->fresh()->hotel->hotelSatisfaction, null);
        $this->assertNotEquals($brand->fresh()->hotel->hotelReview, null);
    }

    public function testReviewBrandPermissionsAreSetCorrectly()
    {
        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $product = $this->productService->getByName('review');

        $this->assertEquals($brand->hotel->hotelReview, null);

        if ($product) {
            $this->json('PUT', "/api/brands/$brand->id/products/$product->id/activate/1")
                ->assertStatus(204);
        }

        Event::assertDispatched(BrandUpdated::class);
    }

    public function testActivateReviewAfterSatisfactionRemainsReviewDesactivated()
    {
        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $satisfactionProduct = $this->productService->getByName('satisfaction');
        $reviewProduct = $this->productService->getByName('review');

        $this->assertEquals($brand->hotel->hotelReview, null);

        $this->json('PUT', "/api/brands/$brand->id/products/$satisfactionProduct->id/activate/1")->assertStatus(204);
        $this->json('PUT', "/api/brands/$brand->id/products/$reviewProduct->id/activate/1")->assertStatus(204);

        Event::assertDispatched(BrandUpdated::class);
        $this->assertNotEquals($brand->fresh()->hotel->hotelReview, null);

        $satisfactionBrandProduct = BrandProduct::where(['brand_id' => $brand->id, 'product_id' => $satisfactionProduct->id])->first();
        $reviewBrandProduct = BrandProduct::where(['brand_id' => $brand->id, 'product_id' => $reviewProduct->id])->first();

        $this->assertEquals($satisfactionBrandProduct->active, 1);
        $this->assertEquals($reviewBrandProduct->active, 0);
    }

    public function testBirthdayEmailsBrandPermissionsAreSetCorrectly()
    {
        $hotel = factory(Hotel::class)->create();
        $hotelSatisfaction = factory(HotelSatisfaction::class)->create([
            'id_hotel'      => $hotel->id,
            'warning_email' => 'testSuccess'
        ]);

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $product = $this->productService->getByName('birthday_emails');

        $oldBrandProduct = factory(BrandProduct::class)->create([
            'brand_id'   => $brand->id,
            'product_id' => $product->id,
            'active'     => 0
        ]);

        if ($product) {
            $this->json('PUT', "/api/brands/$brand->id/products/$product->id/activate/1")
                ->assertStatus(204);
        }

        Event::assertDispatched(BrandUpdated::class);

        $newBrandProduct = $this->brandProductService->get($brand->id, $product->id);

        $this->assertNotEquals($oldBrandProduct->active, $newBrandProduct->active);
        $this->assertEquals($brand->fresh()->hotel->birthdayAlertEmails, 'testSuccess');
    }

    public function testNotHotelBrandPermissionsAreSetCorrectly()
    {
        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $product = $this->productService->getByName('not_hotel');

        $oldBrandProduct = factory(BrandProduct::class)->create([
            'brand_id'   => $brand->id,
            'product_id' => $product->id,
            'active'     => 0
        ]);

        if ($product) {
            $this->json('PUT', "/api/brands/$brand->id/products/$product->id/activate/1")
                ->assertStatus(204);
        }

        Event::assertDispatched(BrandUpdated::class);

        $newBrandProduct = $this->brandProductService->get($brand->id, $product->id);
        $brandEprivacy = BrandEprivacy::where(['hotel_id' => $hotel->id])->first();

        $this->assertNotEquals($oldBrandProduct->active, $newBrandProduct->active);
        $this->assertEquals($brandEprivacy->restricted_portal, 1);
    }

    public function testCustomizedSatisfactionBrandPermissionsAreShowCorrectly()
    {
        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $product = $this->productService->getByName('customized_satisfaction_surveys');

        $oldBrandProduct = factory(BrandProduct::class)->create([
            'brand_id'   => $brand->id,
            'product_id' => $product->id,
            'active'     => 0
        ]);

        if ($product) {
            $this->json('PUT', "/api/brands/$brand->id/products/$product->id/activate/1")
                ->assertStatus(204);
        }

        Event::assertDispatched(BrandUpdated::class);

        $newBrandProduct = $this->brandProductService->get($brand->id, $product->id);

        $this->assertNotEquals($oldBrandProduct->active, $newBrandProduct->active);

        if ($product) {
            $this->json('GET', "/api/brands/$brand->id/products/$product->id/configuration")
                ->assertStatus(200)
                ->assertJson([
                    'customizedSendDays'       => 0,
                    'customizedSendHours'      => 0,
                    'customizedChainActivated' => 0,
                    'brandID'                  => $brand->id
                ]);
        }
    }

    public function testReviewBrandPermissionsAreShowCorrectly()
    {
        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $product = $this->productService->getByName('review');

        $this->assertEquals($brand->hotel->hotelReview, null);

        if ($product) {
            $this->json('PUT', "/api/brands/$brand->id/products/$product->id/activate/1")
                ->assertStatus(204);
        }

        Event::assertDispatched(BrandUpdated::class);

        if ($product) {
            $brand = $brand->fresh();
            $this->json('GET', "/api/brands/$brand->id/products/$product->id/configuration")
                ->assertStatus(200)
                ->assertJson([
                    'brand_id'          => $brand->id,
                    'send_after_days'   => $brand->hotel->hotelReview->diasEnvio,
                    'ignore_rating'     => $brand->hotel->hotelReview->ignoreRating,
                    'active'            => 1
                ]);
        }
    }

    public function testSatisfactionSurveyBrandPermissionsAreShowCorrectly()
    {
        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $product = $this->productService->getByName('satisfaction');

        $this->assertEquals($brand->hotel->hotelSatisfaction, null);

        if ($product) {
            $this->json('PUT', "/api/brands/$brand->id/products/$product->id/activate/1")
                ->assertStatus(204);
        }

        Event::assertDispatched(BrandUpdated::class);

        $brand = $brand->fresh();
        $this->assertNotEquals($brand->hotel->hotelSatisfaction, null);

        if ($product) {
            $this->json('GET', "/api/brands/$brand->id/products/$product->id/configuration")
                ->assertStatus(200)
                ->assertJson([
                    'brandID'            => $brand->id,
                    'warningEmail'       => $brand->hotel->hotelSatisfaction->warning_email,
                    'sendHour'           => $brand->hotel->hotelSatisfaction->send_hour,
                    'totalFollowupEmail' => $brand->hotel->hotelSatisfaction->total_followup_email,
                    'puntMin'            => $brand->hotel->hotelSatisfaction->puntMin,
                    'ignoreRating'       => $brand->hotel->hotelSatisfaction->ignoreRating,
                    'sendThanksMail'     => $brand->hotel->hotelSatisfaction->sendThanksMail,
                    'sendToNonCustomers' => $brand->hotel->hotelSatisfaction->sendToNonCustomers,
                    'sendAfterDays'      => $brand->hotel->hotelSatisfaction->send_days
                ]);
        }
    }

    public function testSatisfactionBrandPermissionsAreSetCorrectly()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $hotelSatisfaction = factory(HotelSatisfaction::class)->create([
            'id_hotel'      => $hotel->id
        ]);

        $product = $this->productService->getByName('satisfaction');
        $payload = $this->mountUserPayload(null, 0);

        $this->json('PUT', "/api/brands/$brand->id/products/$product->id/configuration", $payload)->assertStatus(204);

        // Test configuration updated
        $hotelSatisfactionFresh = $brand->fresh()->hotel->hotelSatisfaction;
        $this->assertEquals($hotelSatisfactionFresh->diasEnvio, data_get($payload, 'sendAfterDays'));
        $this->assertEquals($hotelSatisfactionFresh->puntMin, data_get($payload, 'puntMin'));
        $this->assertEquals($hotelSatisfactionFresh->warning_email, data_get($payload, 'warningEmail'));
        $this->assertEquals($hotelSatisfactionFresh->sendThanksMail, data_get($payload, 'sendThanksMail'));
        $this->assertEquals($hotelSatisfactionFresh->sendToNonCustomers, data_get($payload, 'sendToNonCustomers'));
        $this->assertEquals($hotelSatisfactionFresh->send_hour, data_get($payload, 'sendHour'));
        $this->assertEquals($hotelSatisfactionFresh->total_followup_email, data_get($payload, 'totalFollowupEmail'));
        $this->assertEquals($hotelSatisfactionFresh->filter_warning, data_get($payload, 'filterWarning'));
        $this->assertEquals($hotelSatisfactionFresh->force_comment, data_get($payload, 'forceComment'));
        $this->assertEquals($hotelSatisfactionFresh->review_average_score, data_get($payload, 'reviewAverageScore'));
        $this->assertEquals($hotelSatisfactionFresh->default_score, data_get($payload, 'defaultScore'));
    }

    public function testSatisfactionWarningEmailsByChainAreSetCorrectly()
    {
        $chain = factory(Cadena::class)->create();
        $hotel = factory(Hotel::class)->create();
        $hotel2 = factory(Hotel::class)->create();
        factory(CadenaHotel::class)->create(
            [
                'id_cadena' => $chain->id,
                'id_hotel'  => $hotel->id
            ]
        );
        factory(CadenaHotel::class)->create(
            [
                'id_cadena' => $chain->id,
                'id_hotel'  => $hotel2->id
            ]
        );

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $brand2 = Brand::where('hotel_id', '=', $hotel2->id)->first();
        $parentBrand = Brand::where('chain_id', $chain->id)->first();

        $badHotel = factory(Hotel::class)->create(['id' => $brand->id]);
        $badBrand = Brand::where('hotel_id', '=', $badHotel->id)->first();


        factory(HotelSatisfaction::class)->create([
            'id_hotel'      => $hotel->id
        ]);
        factory(HotelSatisfaction::class)->create([
            'id_hotel'      => $hotel2->id
        ]);
        factory(HotelSatisfaction::class)->create([
            'id_hotel'      => $badHotel->id
        ]);

        $product = $this->productService->getByName('satisfaction');
        $payload = $this->mountUserPayload($parentBrand->id, 1);

        $this->json('PUT', "/api/brands/$brand->id/products/$product->id/configuration", $payload)->assertStatus(204);

        // Test configuration updated
        $this->assertEquals($brand->fresh()->hotel->hotelSatisfaction->warning_email, data_get($payload, 'warningEmail'));
        $this->assertEquals($brand2->fresh()->hotel->hotelSatisfaction->warning_email, data_get($payload, 'warningEmail'));
        $this->assertNotEquals($badBrand->fresh()->hotel->hotelSatisfaction->warning_email, data_get($payload, 'warningEmail'));
    }

    public function testCustomizedSatisfactionBrandPermissionsAreSetCorrectly()
    {
        $chain = factory(Cadena::class)->create();
        $hotel = factory(Hotel::class)->create();
        $notRelatedHotel = factory(Hotel::class)->create();

        factory(CadenaHotel::class)->create(
            [
                'id_cadena' => $chain->id,
                'id_hotel'  => $hotel->id
            ]
        );

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $parentBrand = Brand::where('chain_id', $chain->id)->first();
        $notRelatedBrand = Brand::where('hotel_id', '=', $notRelatedHotel->id)->first();

        $survey = factory(Survey::class)->create(['brand_id' => $brand->id]);
        $parentSurvey = factory(Survey::class)->create(['brand_id' => $parentBrand->id]);
        $notRelatedSurvey = factory(Survey::class)->create(['brand_id' => $notRelatedBrand->id]);

        $user = factory(User::class)->create(['lang' => 'en']);
        $userSatisfactionDone = factory(Satisfaction::class)->create([
            'id_hotel'      => $hotel->id,
            'id_usuario'    => $user->id,
            'puntuacion'    => 10,
            'done'          => 1
        ]);
        $userSatisfactionNotDone = factory(Satisfaction::class)->create([
            'id_hotel'      => $hotel->id,
            'id_usuario'    => $user->id,
            'puntuacion'    => 0,
            'done'          => 0
        ]);
        $notRelatedSatisfactionNotDone = factory(Satisfaction::class)->create([
            'id_hotel'      => $hotel->id,
            'id_usuario'    => $user->id,
            'puntuacion'    => 0,
            'done'          => 0
        ]);

        $category = factory(Category::class)->create(['brand_id' => $brand->id]);
        $question = factory(Question::class)->create(['category_id' => $category->id]);
        $surveyQuestion = factory(SurveyQuestion::class)->create([
            'survey_id' => $survey->id,
            'question_id' => $question->id,
            'active' => 1,
            'required' => 1
        ]);
        $userSurveyDone = factory(UserSurvey::class)->create([
            'survey_id'             => $survey->id,
            'brand_id'              => $brand->id,
            'user_id'               => $user->id,
            'user_satisfaction_id'  => $userSatisfactionDone->id
        ]);
        $userSurveyNotDone = factory(UserSurvey::class)->create([
            'survey_id'             => $survey->id,
            'brand_id'              => $brand->id,
            'user_id'               => $user->id,
            'user_satisfaction_id'  => $userSatisfactionNotDone->id
        ]);
        $notRelatedUserSurveyNotDone = factory(UserSurvey::class)->create([
            'survey_id'             => $notRelatedSurvey->id,
            'brand_id'              => $notRelatedBrand->id,
            'user_id'               => $user->id,
            'user_satisfaction_id'  => $notRelatedSatisfactionNotDone->id
        ]);
        factory(UserSurveyQuestionAnswer::class)->create([
            'user_survey_id'        => $userSurveyDone->id,
            'survey_question_id'    => $surveyQuestion->id,
            'answer'                => 10,
        ]);

        $product = $this->productService->getByName('customized_satisfaction_surveys');

        $oldBrandProduct = factory(BrandProduct::class)->create([
            'brand_id'   => $brand->id,
            'product_id' => $product->id,
            'active'     => 0
        ]);

        if ($product) {
            $this->json('PUT', "/api/brands/$brand->id/products/$product->id/activate/1")
                ->assertStatus(204);
        }

        Event::assertDispatched(BrandUpdated::class);

        $newBrandProduct = $this->brandProductService->get($brand->id, $product->id);

        $this->assertNotEquals($oldBrandProduct->active, $newBrandProduct->active);

        if ($product) {
            $this->json('GET', "/api/brands/$brand->id/products/$product->id/configuration")
                ->assertStatus(200)
                ->assertJson([
                    'customizedSendDays'       => 0,
                    'customizedSendHours'      => 0,
                    'customizedChainActivated' => 0,
                    'brandID'                  => $brand->id
                ]);
        }

        $payload = [
            'customizedActive'         => 1,
            'customizedType'           => 'Joined with satisfaction',
            'customizedSendDays'       => '10',
            'customizedSendHours'      => '5',
            'customizedChainActivated' => '1'
        ];

        $this->json('PUT', "/api/brands/$brand->id/products/$product->id/configuration", $payload)->assertStatus(204);

        // Test configuration updated
        $this->assertEquals($brand->fresh()->hotel->hotelSatisfaction->customized_type, 'Joined with satisfaction');

        // Test change not answered user surveys
        $this->assertEquals($userSurveyDone->fresh()->survey_id, $survey->id);
        $this->assertEquals($userSurveyNotDone->fresh()->survey_id, $parentSurvey->id);
        $this->assertEquals($notRelatedUserSurveyNotDone->fresh()->survey_id, $notRelatedSurvey->id);
    }

    public function testAutocheckinConfig()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = $hotel->brand;
        $product = $this->productService->getByName('autocheckin');

        $autocheckinConfig = [
            'identification'           => [
                'reservation_inputs'  => [
                    [
                        [
                            'name' => 'reservation_code',
                            'type' => 'integer'
                        ],
                        [
                            'name' => 'last_name',
                            'type' => 'string'
                        ]
                    ],
                    [
                        [
                            'name' => 'email',
                            'type' => 'string'
                        ]
                    ]
                ],
                'reservation_filters' => [
                    [
                        [
                            'name' => 'check_in',
                            'type' => 'date'
                        ]
                    ]
                ]
            ],
            "max_attempts_reservation" => 10,
            "child_required_identity_documents_age" => 12,
            "max_attempts_child" => 3,
            "max_attempts_document" => 10,
            "partial_checkin" => false,
            "room_type_selection" => true,
            "telephone" => true,
            "telephone_notifications" => false,
            "max_attempts_telephone" => 3,
            "comments" => false,
            "show_comments_only_on_holder" => false,
            "signed_documents" => true,
            "optional_scan" => false,
            "advanced_scan" => false,
            "custom_scan_text" => false,
            "custom_comments_text" => false,
            "send_identity_documents" => false,
            "time_limit_checkin" => 0,
            "scan_children_like_adults" => false,
            "children_process_on_reception" => false,
            "custom_confirmation_text" => false,
            "send_identity_documents_to_reception" => false,
            "send_signed_documents_to_reception" => false,
            "show_holder" => false,
            "allow_expired_documents" => false,
            "not_allow_passports_from_country_brand" => false,
            "redirect_link" => false,
            "reservation_holder_not_modifiable" => false,
            "custom_gdpr_text" => false,
            "show_qr_code" => true,
            "reception_signature" => false,
            "show_modal_in_confirmation_page" => false,
            "activate_time_limit" => false,
            "send_email_checkin_available" => false,
            "disable_address_autocomplete" => false,
            "disable_only_address_autocomplete" => false,
            "children_sign_documents" => false,
            "show_save_phone_in_database_checkbox" => true,
            "show_send_newsletter_checkbox" => true,
            "custom_phone_text" => false,
            "allow_driving_license" => false,
            "identity_document_signature_required" => false,
            "disable_scan" => false,
            "child_data_with_holder" => false,
            "disable_send_documents_page" => false,
            "arrival_time" => false,
            "second_surname_required_for_spanish" => false,
            "region_and_subregion_with_iso_codes" => false
        ];

        // If the configuration of a brand that has never been activated is requested, it returns the default configuration.

        $this->json('GET', "/api/brands/{$brand->id}/products/{$product->id}/configuration")
            ->assertStatus(200);

        $this->json('PUT', "/api/brands/{$brand->id}/products/{$product->id}/activate/1")
            ->assertStatus(204);

        Event::assertDispatched(BrandUpdated::class);

        $this->json('PUT', "/api/brands/{$brand->id}/products/{$product->id}/configuration", $autocheckinConfig)
            ->assertStatus(204);

        Event::assertDispatched(AutocheckinUpdated::class);

        $this->json('GET', "/api/brands/{$brand->id}/products/{$product->id}/configuration")
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'active'                   => true,
                    'identification'           => [
                        'reservation_inputs'  => [
                            [
                                [
                                    'name' => $autocheckinConfig['identification']['reservation_inputs'][0][0]['name'],
                                    'type' => $autocheckinConfig['identification']['reservation_inputs'][0][0]['type']
                                ],
                                [
                                    'name' => $autocheckinConfig['identification']['reservation_inputs'][0][1]['name'],
                                    'type' => $autocheckinConfig['identification']['reservation_inputs'][0][1]['type']
                                ]
                            ],
                            [
                                [
                                    'name' => $autocheckinConfig['identification']['reservation_inputs'][1][0]['name'],
                                    'type' => $autocheckinConfig['identification']['reservation_inputs'][1][0]['type']
                                ]
                            ]
                        ],
                        'reservation_filters' => [
                            [
                                [
                                    'name' => $autocheckinConfig['identification']['reservation_filters'][0][0]['name'],
                                    'type' => $autocheckinConfig['identification']['reservation_filters'][0][0]['type']
                                ]
                            ]
                        ]
                    ],
                    "max_attempts_reservation"                 => $autocheckinConfig['max_attempts_reservation'],
                    "child_required_identity_documents_age"    => $autocheckinConfig['child_required_identity_documents_age'],
                    "max_attempts_child"                       => $autocheckinConfig['max_attempts_child'],
                    "max_attempts_document"                    => $autocheckinConfig['max_attempts_document'],
                    "partial_checkin"                          => $autocheckinConfig['partial_checkin'],
                    "room_type_selection"                      => $autocheckinConfig['room_type_selection'],
                    "telephone"                                => $autocheckinConfig['telephone'],
                    "telephone_notifications"                  => $autocheckinConfig['telephone_notifications'],
                    "max_attempts_telephone"                   => $autocheckinConfig['max_attempts_telephone'],
                    "comments"                                 => $autocheckinConfig['comments'],
                    "show_comments_only_on_holder"             => $autocheckinConfig['show_comments_only_on_holder'],
                    "signed_documents"                         => $autocheckinConfig['signed_documents'],
                    "optional_scan"                            => $autocheckinConfig['optional_scan'],
                    "advanced_scan"                            => $autocheckinConfig['advanced_scan'],
                    "custom_scan_text"                         => $autocheckinConfig['custom_scan_text'],
                    "custom_comments_text"                     => $autocheckinConfig['custom_comments_text'],
                    "send_identity_documents_to_PMS"           => $autocheckinConfig['send_identity_documents'],
                    "time_limit_checkin"                       => $autocheckinConfig['time_limit_checkin'],
                    "scan_children_like_adults"                => $autocheckinConfig['scan_children_like_adults'],
                    "custom_confirmation_text"                 => $autocheckinConfig['custom_confirmation_text'],
                    "send_identity_documents_to_reception"     => $autocheckinConfig['send_identity_documents_to_reception'],
                    "send_signed_documents_to_reception"       => $autocheckinConfig['send_signed_documents_to_reception'],
                    "show_holder"                              => $autocheckinConfig['show_holder'],
                    "allow_expired_documents"                  => $autocheckinConfig['allow_expired_documents'],
                    "not_allow_passports_from_country_brand"   => $autocheckinConfig['not_allow_passports_from_country_brand'],
                    "children_process_on_reception"            => $autocheckinConfig['children_process_on_reception'],
                    "redirect_link"                            => $autocheckinConfig['redirect_link'],
                    "reservation_holder_not_modifiable"        => $autocheckinConfig['reservation_holder_not_modifiable'],
                    "custom_gdpr_text"                         => $autocheckinConfig['custom_gdpr_text'],
                    "show_qr_code"                             => $autocheckinConfig['show_qr_code'],
                    "reception_signature"                      => $autocheckinConfig['reception_signature'],
                    "show_modal_in_confirmation_page"          => $autocheckinConfig['show_modal_in_confirmation_page'],
                    "activate_time_limit"                      => $autocheckinConfig['activate_time_limit'],
                    "send_email_checkin_available"             => $autocheckinConfig['send_email_checkin_available'],
                    "disable_address_autocomplete"             => $autocheckinConfig['disable_address_autocomplete'],
                    "disable_only_address_autocomplete"        => $autocheckinConfig['disable_only_address_autocomplete'],
                    "children_sign_documents"                  => $autocheckinConfig['children_sign_documents'],
                    "show_save_phone_in_database_checkbox"     => $autocheckinConfig['show_save_phone_in_database_checkbox'],
                    "show_send_newsletter_checkbox"            => $autocheckinConfig['show_send_newsletter_checkbox'],
                    "custom_phone_text"                        => $autocheckinConfig['custom_phone_text'],
                    "allow_driving_license"                    => $autocheckinConfig['allow_driving_license'],
                    "identity_document_signature_required"     => $autocheckinConfig['identity_document_signature_required'],
                    "disable_scan"                             => $autocheckinConfig['disable_scan'],
                    "child_data_with_holder"                   => $autocheckinConfig['child_data_with_holder'],
                    "disable_send_documents_page"              => $autocheckinConfig['disable_send_documents_page'],
                    "arrival_time"                             => $autocheckinConfig['arrival_time'],
                    "second_surname_required_for_spanish"      => $autocheckinConfig['second_surname_required_for_spanish'],
                    "region_and_subregion_with_iso_codes"      => $autocheckinConfig['region_and_subregion_with_iso_codes']
                ]
            ]);
    }

    public function testRequireRoomNumberCreatesDefaultConfiguration()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $product = $this->productService->getByName('require_room_num');

        $this->json('PUT', "/api/brands/$brand->id/products/$product->id/activate/1")
            ->assertStatus(204);

        Event::assertDispatched(BrandUpdated::class);

        $this->assertDatabaseHas('brand_product', ['brand_id' => $brand->id, 'product_id' => $product->id, 'active' => 1]);

        $brandProduct = BrandProduct::where(['brand_id' => $brand->id, 'product_id' => $product->id])->first();
        $producConfig = ProductConfig::where('label', 'display_room_number')->first();
        $this->assertDatabaseHas('brand_product_config', ['brand_product_id' => $brandProduct->id, 'product_config_id' => $producConfig->id, 'value' => 1]);
    }

    public function testGetAllProductsStatus()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        
        // Only create brand_product relation for product1
        factory(BrandProduct::class)->create([
            'brand_id' => $brand->id,
            'product_id' => 1,
            'active' => true
        ]);
        
        $response = $this->json('GET', "/api/brands/$brand->id/products");
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'product_id',
                        'active',
                        'name'
                    ]
                ]
            ]);

        $responseData = $response->json('data');
        
        // Check product with existing relationship
        $product1Response = collect($responseData)->firstWhere('product_id', 1);
        $this->assertTrue($product1Response['active']);
        $this->assertEquals('LY', $product1Response['name']);
        
        // Check product without relationship
        $product2Response = collect($responseData)->firstWhere('product_id', 2);
        $this->assertFalse($product2Response['active']);
        $this->assertEquals('RF', $product2Response['name']);
    }

    public function testGetSpecificProductStatus()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $product = $this->productService->getByName('satisfaction');

        factory(BrandProduct::class)->create([
            'brand_id' => $brand->id,
            'product_id' => $product->id,
            'active' => 1
        ]);

        $response = $this->json('GET', "/api/brands/$brand->id/products/$product->id");
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' =>
                    [
                        'product_id',
                        'active',
                        'name'
                    ]
                ]
            ]);

        $responseData = $response->json('data')[0];
        $this->assertEquals($product->id, $responseData['product_id']);
        $this->assertTrue($responseData['active']);
        $this->assertEquals(str_replace('_', ' ', $product->name), $responseData['name']);
    }

    public function testGetAllProductsStatusForNonExistentBrand()
    {
        $nonExistentBrandId = 99999;
        $response = $this->json('GET', "/api/brands/$nonExistentBrandId/products");

        $response->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    public function testGetSpecificProductStatusForNonExistentProduct()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $nonExistentProductId = 99999;

        $response = $this->json('GET', "/api/brands/$brand->id/products/$nonExistentProductId");

        $response->assertStatus(404);
    }

    public function testGetSpecificProductStatusForExistingProductWithoutBrandRelation()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $product = $this->productService->getByName('satisfaction');

        // Don't create brand_product relation

        $response = $this->json('GET', "/api/brands/$brand->id/products/$product->id");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'product_id',
                        'active',
                        'name'
                    ]
                ]
            ]);

        $responseData = $response->json('data')[0];

        $this->assertEquals($product->id, $responseData['product_id']);
        $this->assertFalse($responseData['active']);
        $this->assertEquals(str_replace('_', ' ', $product->name), $responseData['name']);
    }

    private function mountUserPayload(int $parentBrandId = null, int $chainEmail = null): array
    {
        $faker = Factory::create();

        return [
            'sendHour'           => $faker->numberBetween(0, 23),
            'totalFollowupEmail' => $faker->numberBetween(0, 5),
            'puntMin'            => $faker->numberBetween(0, 10),
            'reviewAverageScore' => $faker->numberBetween(0, 10),
            'chainEmail'         => !is_null($chainEmail) ? $chainEmail : $faker->numberBetween(0, 1),
            'parentID'           => $parentBrandId ? $parentBrandId : null,
            'sendThanksMail'     => $faker->numberBetween(0, 1),
            'sendToNonCustomers' => $faker->numberBetween(0, 1),
            'sendAfterDays'      => $faker->numberBetween(0, 31),
            'filterWarning'      => $faker->numberBetween(0, 1),
            'forceComment'       => $faker->numberBetween(0, 1),
            'defaultScore'       => $faker->numberBetween(0, 10),
            'warningEmail'       => implode(",", [$faker->email(), $faker->email()])
        ];
    }
}
