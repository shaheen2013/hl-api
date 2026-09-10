<?php

namespace Tests\Feature;

use App\Brand;
use App\Cadena;
use App\Hotel;
use App\CadenaHotel;
use App\User;
use App\UserHotel;
use App\UserBrand;
use App\Visit;
use App\UserVisits;
use App\Device;
use App\Connection;
use App\Http\Middleware\AmazonRequest;
use Mockery;
use Tests\CreatesApplication;
use Illuminate\Support\Arr;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserControllerTest extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    protected $expectedResponseMapping;

    public function setUp(): void
    {
        parent::setUp();

        $this->expectedResponseMapping = [
            'external' => [
                'keys' => [
                    "id",
                    "email",
                    "name",
                    "first_name",
                    "last_name",
                    "document_id",
                    "birthday",
                    "gender",
                    "locale",
                    "lang",
                    "country",
                    "facebook",
                    "email_result",
                    "sendex",
                    "unsubscribed",
                    "gdpr_accepted",
                    "brand",
                    "created_at",
                    "phone_number",
                    "visit",
                    "reservation"
                ],
                'error' => [
                    400 => "The brands on list do not belong to same chain.",
                    500 => config('external-api.default-error-message')
                ]
            ],
            'internal' => [
                'keys' => [
                    "id",
                    "email",
                    "name",
                    // "first_name",
                    // "last_name",
                    // "document_id",
                    "birthday",
                    "gender",
                    "location",
                    "lang",
                    "country",
                    // "facebook",
                    "email_result",
                    "sendex",
                    // "unsubscribed",
                    "brand",
                    // "created_at",
                    "reservation"
                ],
                'error' => [
                    400 => "The brands on list do not belong to same chain.",
                    500 => 'No query results for model [App\Brand] X'
                ]
            ],
        ];
    }

    public function testGetBrandUsersUsingBrandIdForAHotel()
    {
        $nHotels = 1;
        $isChain = true;
        $nUsers = 10;
        $nUnsubscribedUsers = null;
        $nUsersVisitPassed = null;
        $subDaysOnVisit = 0;
        // Get hotels and users
        $hotels = $this->prepareBrandUsers($nHotels, $isChain, $nUsers, $nUnsubscribedUsers, $nUsersVisitPassed, $subDaysOnVisit);

        $hotel = $hotels->first();
        $brand = $hotel->brand;
        $users = $brand->users;
        $usersIds = $users->pluck('user_id');

        // ---- Assert that the endpoint get all users
        // Perform request to get surveys
        $response = $this
            ->json('GET', "/api/brands/{$brand->id}/users")
            ->assertStatus(200);

        // Get response content
        $content = $response->json();
        $responseIds = collect($content['data'])->pluck('id');

        // Check if the ids on the DB has all ids that become on response
        $this->assertEquals($nUsers, $responseIds->count());
        $this->assertEquals(1, $content['meta']['current_page']);
        $this->assertEquals(config('services.users.paginate.default'), $content['meta']['per_page']);
        $this->assertTrue($responseIds->diff($usersIds)->isEmpty());

        // ---- Assert Paginate works that the endpoint get page 1 of users
        $page = 1;
        $itemsPerPage = round($nUsers / 2);
        // Perform request to get surveys
        $response = $this
            ->json('GET', "/api/brands/{$brand->id}/users?page=$page&page_items=$itemsPerPage")
            ->assertStatus(200);

        // Get response content
        $content = $response->json();
        $responseIds = collect($content['data'])->pluck('id');

        // Check if the ids on the DB has all ids that become on response
        $this->assertEquals($itemsPerPage, $responseIds->count());
        $this->assertEquals($page, $content['meta']['current_page']);
        $this->assertEquals($itemsPerPage, $content['meta']['per_page']);
        $this->assertTrue($responseIds->diff($usersIds)->isEmpty());

        // ---- Assert Paginate works that the endpoint get all users
        $page = 1;
        $itemsPerPage = $nUsers;
        // Perform request to get surveys
        $response = $this
            ->json('GET', "/api/brands/{$brand->id}/users?page=$page&page_items=$itemsPerPage")
            ->assertStatus(200);

        // Get response content
        $content = $response->json();
        $responseIds = collect($content['data'])->pluck('id');

        // Check if the ids on the DB has all ids that become on response
        $this->assertEquals($nUsers, $responseIds->count());
        $this->assertEquals($page, $content['meta']['current_page']);
        $this->assertEquals($nUsers, $content['meta']['per_page']);
        $this->assertTrue($responseIds->diff($usersIds)->isEmpty());
    }

    public function testGetBrandUsersUsingBrandIdForAChain()
    {
        $nHotels = 3;
        $isChain = true;
        $nUsers = 10;
        $nUnsubscribedUsers = null;
        $nUsersVisitPassed = null;
        $subDaysOnVisit = 0;
        // Get hotels and users
        $hotels = $this->prepareBrandUsers($nHotels, $isChain, $nUsers, $nUnsubscribedUsers, $nUsersVisitPassed, $subDaysOnVisit);
        // Get all userIds
        $usersIds = $hotels->reduce(function ($carry, $hotel) {
            $brand = $hotel->brand;
            $users = $brand->users;
            $carry = array_merge($carry, $users->pluck('user_id')->toArray());
            return $carry;
        }, []);

        // Get brand_id from chain
        $brand = $hotels->first()->brand;
        $chainBrandId = $brand->parent_id;
        $hotelBrandId = $brand->id;

        $response = $this
            ->json('GET', "/api/brands/{$chainBrandId}/users")
            ->assertStatus(200);

        // Get response content
        $content = $response->json();
        $responseIds = collect($content['data'])->pluck('id');

        // Check if the ids on the DB has all ids that become on response
        $this->assertEquals($nUsers * $nHotels, $responseIds->count());
        $this->assertEquals(1, $content['meta']['current_page']);
        $this->assertEquals(config('services.users.paginate.default'), $content['meta']['per_page']);
        $this->assertTrue($responseIds->diff($usersIds)->isEmpty());

        // ---- Assert 400 when passing a brands with a brand_id from another hotel
        $wrongBrandId = Brand::pluck('id')->last() + 1;
        // Perform request to get users
        $response = $this
            ->json('GET', "/api/brands/{$chainBrandId}/users?brands=$hotelBrandId,$wrongBrandId")
            # TODO: Change this statusCode to 500
            ->assertStatus(400);
    }

    public function testGetBrandUsersWithDateFromAndDateToFilters()
    {
        $nHotels = 1;
        $isChain = true;
        $nUsers = 10;
        $nUnsubscribedUsers = 3;
        $nUsersVisitPassed = 10;
        $subDaysOnVisit = 30;
        // Get hotels and users
        $hotels = $this->prepareBrandUsers($nHotels, $isChain, $nUsers, $nUnsubscribedUsers, $nUsersVisitPassed, $subDaysOnVisit);

        $hotel = $hotels->first();
        $brand = $hotel->brand;
        $users = $brand->users;
        $usersIds = $users->pluck('user_id');

        // ---- Assert that the endpoint get all users from a date
        // Perform request to get users using dateFrom
        $dateFrom = now()->subDays($subDaysOnVisit - 1)->toDateString();
        $response = $this
            ->json('GET', "/api/brands/{$brand->id}/users?date_from={$dateFrom}")
            ->assertStatus(200);

        // Get response content
        $content = $response->json();
        $responseIds = collect($content['data'])->pluck('id');

        // Check if the ids on the DB has all ids that become on response
        $this->assertEquals($nUsers + $nUnsubscribedUsers, $responseIds->count());
        $this->assertEquals(1, $content['meta']['current_page']);
        $this->assertEquals(config('services.users.paginate.default'), $content['meta']['per_page']);
        $this->assertTrue($responseIds->count() < $usersIds->count());
        $this->assertTrue($responseIds->diff($usersIds)->isEmpty());

        // ---- Assert that the endpoint get all users until a date
        // Perform request to get users using dateTo
        $dateTo = now()->subDays(1)->toDateString();
        $response = $this
            ->json('GET', "/api/brands/{$brand->id}/users?date_to={$dateTo}")
            ->assertStatus(200);

        // Get response content
        $content = $response->json();
        $responseIds = collect($content['data'])->pluck('id');

        // Check if the ids on the DB has all ids that become on response
        $this->assertEquals($nUsersVisitPassed, $responseIds->count());
        $this->assertEquals(1, $content['meta']['current_page']);
        $this->assertEquals(config('services.users.paginate.default'), $content['meta']['per_page']);
        $this->assertTrue($responseIds->count() < $usersIds->count());
        $this->assertTrue($responseIds->diff($usersIds)->isEmpty());

        // ---- Assert that the endpoint get all nothing by date filters
        // Perform request to get users using dateFrom and dateTo
        $dateFrom = now()->subDays($subDaysOnVisit + 2)->toDateString();
        $dateTo = now()->subDays($subDaysOnVisit + 1)->toDateString();
        $response = $this
            ->json('GET', "/api/brands/{$brand->id}/users?date_to={$dateTo}&date_from={$dateFrom}")
            ->assertStatus(200);

        // Get response content
        $content = $response->json();
        $responseIds = collect($content['data'])->pluck('id');

        // Check if the ids on the DB has all ids that become on response
        $this->assertEquals(0, $responseIds->count());

        $response = $this
            ->json('GET', "/api/brands/{$brand->id}/users?unsubscribed=1")
            ->assertStatus(200);

        $content = $response->json();
        $responseIds = collect($content['data'])->pluck('id');

        $this->assertEquals($nUnsubscribedUsers, $responseIds->count());

        $response = $this
            ->json('GET', "/api/brands/{$brand->id}/users?unsubscribed=0")
            ->assertStatus(200);

        $content = $response->json();
        $responseIds = collect($content['data'])->pluck('id');

        $this->assertEquals($nUsers + $nUsersVisitPassed, $responseIds->count());
    }

    public function testResponseChangesWhenRequestComesFromInternalApiOrExternalApi()
    {
        // Mock middleware to simulate a request from externalApiGateway
        $mockMiddleware = Mockery::mock(AmazonRequest::class . '[isLocalOrTest]');
        $mockMiddleware
            ->shouldReceive('isLocalOrTest')
            ->times(6)
            ->andReturnFalse();

        // Inject the mockInstance
        \App::instance(AmazonRequest::class, $mockMiddleware);

        $nHotels = 1;
        $isChain = true;
        $nUsers = 10;
        $nUnsubscribedUsers = null;
        $nUsersVisitPassed = null;
        $subDaysOnVisit = 0;
        // Get hotels and users
        $hotels = $this->prepareBrandUsers($nHotels, $isChain, $nUsers, $nUnsubscribedUsers, $nUsersVisitPassed, $subDaysOnVisit);

        $hotel = $hotels->first();
        $brand = $hotel->brand;
        $users = $brand->users;
        $usersIds = $users->pluck('user_id');

        // Get brand_id from chain
        $chainBrandId = $brand->parent_id;
        $hotelBrandId = $brand->id;
        $wrongBrandId = Brand::pluck('id')->last() + 1;

        // ------ TEST EXTERNAL CALL
        // Perform request to get users
        $response = $this
            ->json(
                'GET',
                "/api/brands/{$chainBrandId}/users",
                ['Accept' => 'application/json'],
                ['x-amzn-apigateway-api-id' => 'external']
            )
            ->assertStatus(200);

        // Get response content
        $content = $response->json();
        // Test keys for external calls
        $resposeKeys = array_keys(Arr::first($content['data']));
        $this->assertEqualsCanonicalizing($this->expectedResponseMapping['external']['keys'], $resposeKeys);
        $this->assertNotEquals(data_get($content, 'data.0.visit.room_number'), null);

        // ------ TEST INTERNAL CALL
        // Perform request to get surveys
        $response = $this
            ->json(
                'GET',
                "/api/brands/{$chainBrandId}/users",
                ['Accept' => 'application/json'],
                ['x-amzn-apigateway-api-id' => 'internal']
            )
            ->assertStatus(200);

        // Get response content
        $content = $response->json();
        $resposeKeys = array_keys(Arr::first($content['data']));

        $this->assertEqualsCanonicalizing($this->expectedResponseMapping['internal']['keys'], $resposeKeys);

        // ----- TEST error 400 message on EXTERNAL call
        // Perform request to get surveys
        $response = $this
            ->json(
                'GET',
                "/api/brands/{$chainBrandId}/users?brands=$hotelBrandId,$wrongBrandId",
                ['Accept' => 'application/json'],
                ['x-amzn-apigateway-api-id' => 'external']
            )
            ->assertStatus(400);

        // Get response content
        $content = $response->json();
        $responseMessage = $content['errors'];

        $this->assertEquals($this->expectedResponseMapping['external']['error'][400], $responseMessage);

        // ----- TEST error 400 message on INTERNAL call
        // Perform request to get surveys
        $response = $this
            ->json(
                'GET',
                "/api/brands/{$chainBrandId}/users?brands=$hotelBrandId,$wrongBrandId",
                ['Accept' => 'application/json'],
                ['x-amzn-apigateway-api-id' => 'internal']
            )
            ->assertStatus(400);

        // Get response content
        $content = $response->json();
        $responseMessage = $content['errors'];

        $this->assertEquals($this->expectedResponseMapping['internal']['error'][400], $responseMessage);

        // ----- TEST error 500 message on EXTERNAL call
        // Perform request to fail when do not exist brandId
        $response = $this
            ->json(
                'GET',
                "/api/brands/{$wrongBrandId}/users",
                ['Accept' => 'application/json'],
                ['x-amzn-apigateway-api-id' => 'external']
            )
            # TODO: Change this statusCode to 500
            ->assertStatus(404);

        // Get response content
        $content = $response->json();
        $responseMessage = $content['errors'];

        $this->assertEquals($this->expectedResponseMapping['external']['error'][500], $responseMessage);

        // ----- TEST error 500 message on INTERNAL call
        // Perform request to fail when do not exist brandId
        $response = $this
            ->json(
                'GET',
                "/api/brands/{$wrongBrandId}/users",
                ['Accept' => 'application/json'],
                ['x-amzn-apigateway-api-id' => 'internal']
            )
            # TODO: Change this statusCode to 500
            ->assertStatus(404);

        // Get response content
        $content = $response->json();
        $responseMessage = str_replace($wrongBrandId, 'X', $content['errors']);

        $this->assertEquals($this->expectedResponseMapping['internal']['error'][500], $responseMessage);
    }

    public function testGetBrandUserUsingBrandIdAndUserId()
    {
        $nHotels = 3;
        $isChain = true;
        $nUsers = 10;
        $nUnsubscribedUsers = null;
        $nUsersVisitPassed = null;
        $subDaysOnVisit = 0;
        // Get hotels and users
        $hotels = $this->prepareBrandUsers($nHotels, $isChain, $nUsers, $nUnsubscribedUsers, $nUsersVisitPassed, $subDaysOnVisit);
        // Get all first user of each hotel
        $firstUserIdFromEachHotel = $hotels->reduce(function ($carry, $hotel) {
            $brand = $hotel->brand;
            $users = $brand->users;
            $carry[$brand->id] = $users->pluck('user_id')->first();
            return $carry;
        }, []);

        // Get first user of the first hotel
        $brandId = array_keys($firstUserIdFromEachHotel)[0];
        $userId = $firstUserIdFromEachHotel[$brandId];
        $response = $this
            ->json('GET', "/api/brands/{$brandId}/users/{$userId}")
            ->assertStatus(200);

        // Get response content
        $content = $response->json();
        $responseUser = $content['data'];

        // Check if the ids on the DB has all ids that become on response
        $this->assertEquals($userId, $responseUser['id']);
        $this->assertEquals($brandId, $responseUser['brand']['id']);

        // Try get user from first hotel on second hotel
        $firstBrandId = array_keys($firstUserIdFromEachHotel)[0];
        $secondBrandId = array_keys($firstUserIdFromEachHotel)[1];
        $userId = $firstUserIdFromEachHotel[$firstBrandId];
        $response = $this
            ->json('GET', "/api/brands/{$secondBrandId}/users/{$userId}")
            ->assertStatus(404);

        // ---- Assert 400 when passing a brands with a brand_id from another hotel
        $wrongBrandId = Brand::pluck('id')->last() + 1;
        // Perform request to get user
        $response = $this
            ->json('GET', "/api/brands/{$wrongBrandId}/users/{$userId}")
            ->assertStatus(404);
    }

    public function testFilterValidationRules()
    {
        $nHotels = 1;
        $isChain = true;
        $nUsers = 10;
        $nUnsubscribedUsers = null;
        $nUsersVisitPassed = null;
        $subDaysOnVisit = 0;
        // Get hotels and users
        $hotels = $this->prepareBrandUsers($nHotels, $isChain, $nUsers, $nUnsubscribedUsers, $nUsersVisitPassed, $subDaysOnVisit);

        $hotel = $hotels->first();
        $brand = $hotel->brand;
        $users = $brand->users;
        $usersIds = $users->pluck('user_id');

        // Get brand_id from chain
        $chainBrandId = $brand->parent_id;
        $hotelBrandId = $brand->id;
        $wrongBrandId = Brand::pluck('id')->last() + 1;

        // Set function to assert all validations
        $assertRequestFn = function ($brandId, $responseCode) {
            return function ($val, $key) use ($brandId, $responseCode) {
                $response = $this
                    ->json(
                        'GET',
                        "/api/brands/{$brandId}/users?$key=$val",
                        ['Accept' => 'application/json'],
                        []
                    )
                    ->assertStatus($responseCode);
            };
        };

        // Assert 400 responses validations
        collect([
            'page_items' => 'x',
            'page_items' => config('services.users.paginate.max', 500) + 1,
            'page_items' => 0,
            'page' => 'x',
            'brands' => 0,
            'brands' => 'x',
            'brands' => $wrongBrandId,
            'language' => 1,
            'language' => 'pt_br',
            'language' => 'xxx',
            'language' => 'x',
            'date_from' => 0,
            'date_from' => '2019',
            'date_from' => '01-02-2019',
            'date_from' => '2019/01/01',
            'date_from' => '2019-99-99',
            'date_from' => '2019-01-01 00:00:00',
            'date_to' => 0,
            'date_to' => '2019',
            'date_to' => '01-02-2019',
            'date_to' => '2019/01/01',
            'date_to' => '2019-99-99',
            'date_to' => '2019-01-01 00:00:00',
        ])
            ->each($assertRequestFn($chainBrandId, 400));

        // Assert 200 responses validations
        collect([
            'page_items' => 1,
            'page_items' => config('services.users.paginate.max', 500),
            'page' => 1,
            'brands' => $hotelBrandId,
            'language' => 'es',
            'language' => 'en',
            'date_from' => '2019-01-01',
            'date_to' => '2019-01-01',
        ])
            ->each($assertRequestFn($chainBrandId, 200));
    }

    public function testUnsubscribeUserSuccessfully()
    {
        $hotel = factory(Hotel::class)->create();
        $hotel2 = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $brand2 = Brand::where('hotel_id', '=', $hotel2->id)->first();

        $suscribedUser = factory(User::class)->create();
        $suscribedUserOtherBrand = factory(User::class)->create();

        $suscribedUserBrand = factory(UserBrand::class)->create([
            'brand_id'      => $brand->id,
            'user_id'       => $suscribedUser->id,
            'unsubscribed'  => 0
        ]);
        $suscribedUserHotel = factory(UserHotel::class)->create([
            'id_usuario' => $suscribedUser->id,
            'id_hotel' =>  $hotel->id,
            'unsubscribed' => 0
        ]);

        $suscribedUserOtherUserBrand = factory(UserBrand::class)->create([
            'brand_id'      => $brand2->id,
            'user_id'       => $suscribedUserOtherBrand->id,
            'unsubscribed'  => 0
        ]);
        $suscribedUserOtherUserHotel = factory(UserHotel::class)->create([
            'id_usuario' => $suscribedUserOtherBrand->id,
            'id_hotel' =>  $hotel2->id,
            'unsubscribed' => 0
        ]);

        $payload = [
            'unsubscribe_notifications' => true,
            'unsubscribe_commercial_profile' => false,
        ];

        $this->json('PUT', "/api/brands/{$brand->id}/users/{$suscribedUser->id}/unsubscribe", $payload)->assertStatus(200);

        $this->assertEquals($suscribedUserBrand->fresh()->unsubscribed, 1);
        $this->assertEquals($suscribedUserHotel->fresh()->unsubscribed, 1);

        $this->assertEquals($suscribedUserOtherUserBrand->fresh()->unsubscribed, 0);
        $this->assertEquals($suscribedUserOtherUserHotel->fresh()->unsubscribed, 0);
    }

    public function testQueueBulkUnsubscribeSuccessfully()
    {
        $this->json('PUT', "/api/brands/1/bulk-unsubscribe", ['user_ids' => [1, 2, 3]])->assertStatus(202);
    }

    public function testQueueBulkUnsubscribeFailsWithInvalidParams()
    {
        $this->json('PUT', "/api/brands/1/bulk-unsubscribe", ['user_ids' => ['invalidId', 1]])->assertStatus(400);
    }

    public function testGetRelatedUsersInBrandWithAllCases()
    {
        $hotel = factory(Hotel::class)->create();
        $hotel2 = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $brand2 = Brand::where('hotel_id', '=', $hotel2->id)->first();

        $suscribedUser = factory(User::class)->create();
        $suscribedUserOtherBrand = factory(User::class)->create();
        $suscribedUserBothBrand = factory(User::class)->create();
        $unsubscribedUser = factory(User::class)->create();

        factory(UserBrand::class)->create([
            'brand_id'      => $brand->id,
            'user_id'       => $suscribedUser->id,
            'unsubscribed'  => 0
        ]);
        factory(UserBrand::class)->create([
            'brand_id'      => $brand2->id,
            'user_id'       => $suscribedUserOtherBrand->id,
            'unsubscribed'  => 0
        ]);
        factory(UserBrand::class)->create([
            'brand_id'      => $brand->id,
            'user_id'       => $suscribedUserBothBrand->id,
            'unsubscribed'  => 0
        ]);
        factory(UserBrand::class)->create([
            'brand_id'      => $brand2->id,
            'user_id'       => $suscribedUserBothBrand->id,
            'unsubscribed'  => 0
        ]);
        factory(UserBrand::class)->create([
            'brand_id'      => $brand->id,
            'user_id'       => $unsubscribedUser->id,
            'unsubscribed'  => 1
        ]);

        $userService = app('App\Services\Users\UserService');
        $result = $userService->getRelatedUsersInBrand($brand->id, [PHP_INT_MAX, $suscribedUserOtherBrand->id, $suscribedUser->id, $suscribedUserBothBrand->id, $unsubscribedUser->id]);

        $this->assertEquals(Arr::get($result, 'usersToUpdate'), [$suscribedUser->id, $suscribedUserBothBrand->id]);
        $this->assertEquals(Arr::get($result, 'unsubscribedUsers'), [$unsubscribedUser->id]);
        $this->assertEquals(Arr::get($result, 'usersNotInBrand'), [PHP_INT_MAX, $suscribedUserOtherBrand->id]);
    }

    public function testGetRelatedUsersInBrandWithOnlyGoodValues()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();

        $suscribedUser = factory(User::class)->create();

        factory(UserBrand::class)->create([
            'brand_id'      => $brand->id,
            'user_id'       => $suscribedUser->id,
            'unsubscribed'  => 0
        ]);

        $userService = app('App\Services\Users\UserService');
        $result = $userService->getRelatedUsersInBrand($brand->id, [$suscribedUser->id]);

        $this->assertEquals(Arr::get($result, 'usersToUpdate'), [$suscribedUser->id]);
        $this->assertEquals(Arr::get($result, 'unsubscribedUsers'), []);
        $this->assertEquals(Arr::get($result, 'usersNotInBrand'), []);
    }

    public function testGetRelatedUsersInBrandWithOnlyBadValues()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();

        $unsubscribedUser = factory(User::class)->create();

        factory(UserBrand::class)->create([
            'brand_id'      => $brand->id,
            'user_id'       => $unsubscribedUser->id,
            'unsubscribed'  => 1
        ]);

        $userService = app('App\Services\Users\UserService');
        $result = $userService->getRelatedUsersInBrand($brand->id, [PHP_INT_MAX, $unsubscribedUser->id]);

        $this->assertEquals(Arr::get($result, 'usersToUpdate'), []);
        $this->assertEquals(Arr::get($result, 'unsubscribedUsers'), [$unsubscribedUser->id]);
        $this->assertEquals(Arr::get($result, 'usersNotInBrand'), [PHP_INT_MAX]);
    }

    /**
     * Function to generate users for each hotel
     *
     * @param int $nUsers
     * @param bool $unsubscribed
     * @param int|null $subDaysOnVisit
     *
     * @return Callable
     */

    public function testGetVisitedBrandsByEmail()
    {
        $hotel = factory(Hotel::class)->create();
        $hotel2 = factory(Hotel::class)->create();
        $brand = Brand::where(
            'hotel_id',
            '=',
            $hotel->id
        )->first();
        $brand2 = Brand::where(
            'hotel_id',
            '=',
            $hotel2->id
        )->first();
        $user = factory(User::class)->create([
           'email' => 'correo@prueba.com',
           'nombre' => 'Francisco',
        ]);
        $userBrand = factory(UserBrand::class)->create([
           'user_id' => $user->id,
           'brand_id' => $brand->id,
        ]);
        $userBrand2 = factory(UserBrand::class)->create([
           'user_id' => $user->id,
           'brand_id' => $brand2->id,
        ]);
        $expectedData = [
           [
               'user_id' => $userBrand->user_id,
               'brand_id' => $userBrand->brand_id,
               'date' => $userBrand->date->format('Y-m-d H:i:s'),
               'unsubscribed' => $userBrand->unsubscribed,
               'email' => $user->email,
               'name' => $user->nombre,
               'hotel_id' => $brand->hotel_id,
           ],
           [
               'user_id' => $userBrand2->user_id,
               'brand_id' => $userBrand2->brand_id,
               'date' => $userBrand2->date->format('Y-m-d H:i:s'),
               'unsubscribed' => $userBrand2->unsubscribed,
               'email' => $user->email,
               'name' => $user->nombre,
               'hotel_id' => $brand2->hotel_id,
           ],
        ];
       
        $response = $this
           ->json('GET', "api/user_brands/{$user-> email}")
           ->assertStatus(200);
        $content = $response->json();

        $this->assertNotEmpty($content);
        $response->assertJsonStructure([
           '*' => [
               'user_id',
               'brand_id',
               'date',
               'unsubscribed',
               'email',
               'name',
               'hotel_id',
           ],
        ]);
        $this->assertCount(2, $content);
        $this->assertEquals($expectedData, $content);
    }

    private function createUsersOnHotel(int $nUsers, bool $unsubscribed, int $subDaysOnVisit = 0)
    {
        return function ($hotel) use ($nUsers, $unsubscribed, $subDaysOnVisit) {
            // Locate brand for this hotel
            $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
            $users = factory(User::class, $nUsers)->create();

            $users->each(function ($user) use ($hotel, $brand, $unsubscribed, $subDaysOnVisit) {
                $userBrand = factory(UserBrand::class)->create([
                    'brand_id'  => $brand->id,
                    'user_id'   => $user->id,
                    'date'      => now()->subDays($subDaysOnVisit)->setTimezone('UTC'),
                    'unsubscribed'  => $unsubscribed
                ]);

                $visit = factory(Visit::class)->create([
                    'user_brand_id' => $userBrand->id,
                    'brand_id'      => $userBrand->brand_id,
                    'user_id'       => $userBrand->user_id,
                    'check_out'     => now()->subDays($subDaysOnVisit)->addDays(5)->setTimezone('UTC')
                ]);

                factory(UserVisits::class)->create([
                    'hotel_id'   => $hotel->id,
                    'user_id'    => $user->id,
                    'num_visits' => 1,
                    'last_login' => now()->subDays($subDaysOnVisit)->setTimezone('UTC'),
                    'recurrent'  => 1
                ]);

                $device = factory(Device::class)->create();
                factory(Connection::class)->create([
                    'brand_id'  => $brand->id,
                    'visit_id'  => $visit->id,
                    'device_id' => $device->id
                ]);
            });
        };
    }

    /**
     * Create hotels and users
     *
     * @param int $nHotels Number of Hotels
     * @param bool $isChain Define if the hotel created belongs to a chain
     * @param int $nUsers Number of Users for each hotel
     * @param int|null $nUnsubscribedUsers Number of unsubscribed users for each hotel
     * @param int|null $nUsersVisitPassed Number of users with visit passed
     * @param int $subDaysOnVisit Move back the dates of visits in days
     *
     * @return array
     */
    private function prepareBrandUsers(int $nHotels = 1, bool $isChain = true, int $nUsers = 10, ?int $nUnsubscribedUsers = null, ?int $nUsersVisitPassed = null, int $subDaysOnVisit = 10)
    {
        // Create hotels and chain
        if ($isChain) {
            $chain = factory(Cadena::class)->create();

            $hotels = collect(range(1, $nHotels))
                ->map(function ($index) use ($chain) {
                    $hotel = factory(Hotel::class)->create();
                    factory(CadenaHotel::class)->create(
                        [
                            'id_cadena' => $chain->id,
                            'id_hotel'  => $hotel->id
                        ]
                    );
                    return $hotel;
                });
        } else {
            $hotels = factory(Hotel::class, $nHotels)->create();
        }

        // Create subscribed users
        $hotels->each($this->createUsersOnHotel($nUsers, false));

        if ($nUnsubscribedUsers) {
            // Create unsubscribed users
            $hotels->each($this->createUsersOnHotel($nUnsubscribedUsers, true));
        }

        if ($nUsersVisitPassed) {
            // Create users with visits passed
            $hotels->each($this->createUsersOnHotel($nUsersVisitPassed, false, $subDaysOnVisit));
        }

        return $hotels;
    }
}
