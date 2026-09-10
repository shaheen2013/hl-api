<?php

namespace Tests\Feature;

use App\User;
use App\UserHotel;
use App\Hotel;
use App\Brand;
use App\Cadena;
use App\CadenaHotel;
use App\Events\UserCreated;
use App\Events\UserCreatedEvent;
use App\Events\UserUpdatedEvent;
use App\Exceptions\InvalidRequestException;
use App\Repositories\Visits\UserBrandRepository;
use App\UserBrand;
use Carbon\Carbon;
use Faker\Factory;
use Hotelinking\Services\ApiGatewayConnection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class UserTest extends TestCase
{
    protected $hotel;
    protected $chain;
    protected $brand;
    protected $user;
    protected $userService;

    public function setUp(): void
    {
        parent::setUp();

        $this->hotel = factory(Hotel::class)->create();
        $this->chain = factory(Cadena::class)->create();
        factory(CadenaHotel::class)->create([
            'id_cadena' => $this->chain->id,
            'id_hotel'  => $this->hotel->id
        ]);
        $this->brand = Brand::where('hotel_id', $this->hotel->id)->first();
        $this->userService = app('App\Services\Users\UserService');
    }

    public function mountUserPayload(array $config = [], $email = null, $firstName = null): array
    {
        $faker = Factory::create();
        $defaults = [
            "email" => $email ?? $faker->email(),
            "first_name" => $firstName ?? $faker->firstName(),
            "last_name" => $faker->lastName,
            "lang" => $faker->languageCode,
            "gender" => "male",
            "birthday" => Carbon::now()->subYears(25)->format('Y-m-d'),
            "locale" => $faker->locale,
            "document_number" => "12345678A",
            "email_result" => "deliverable",
            "country" => $faker->country,
            "sendex" => $faker->randomFloat(2, 0, 0.99),
            "unsubscribed" => 0,
            "customer" => $faker->numberBetween(0, 1),
            "pms_id" => (string) $faker->randomNumber(5),
            "origin" => $faker->randomElement(['form', 'google', 'facebook']),
            "commercial_profile" => 1,
        ];

        // If age is provided, convert it to a birthday
        if (isset($config['age'])) {
            $config['birthday'] = Carbon::now()->subYears($config['age'])->format('Y-m-d');
            unset($config['age']);
        }

        // Merge the defaults with the config array, allowing config to override defaults
        $payload = array_merge($defaults, $config);

        return $payload;
    }

    // Test users are created correctly and data is inserted in with user_hotels, new_user_brand and user_guid tables
    public function testAdultUsersAndRelatedTablesRowsAreCreated()
    {

        $payload = $this->mountUserPayload();
        $this->json('POST', "/api/brands/" . $this->brand->id . "/users", ["data" => $payload])
            ->assertStatus(200);

        $this->assertDatabaseHas('users', [
            "email" => $payload["email"],
            "nombre" => $payload["first_name"] . ' ' . $payload["last_name"],
            "first_name" => $payload["first_name"],
            "last_name" => $payload["last_name"],
            "lang" => $payload["lang"],
            "sexo" => $payload["gender"],
            "fecha_nacimiento" => $payload["birthday"],
            "location" => $payload["locale"],
            "user_card" => $payload["document_number"],
            "email_result" => $payload["email_result"],
            "pais" => $payload["country"],
            "sendex" => $payload["sendex"],
            "unsubscribed" => $payload["unsubscribed"],
            "generation" => $this->userService->getGeneration($payload["birthday"])
        ]);
        $user = User::where("email", $payload['email'])->first();

        $this->assertDatabaseHas('user_hotels', [
            "id_hotel"      => $this->hotel->id,
            "id_cadena"     => $this->chain->id,
            "id_usuario"    => $user->id,
            "user_hotel_id" => $payload['pms_id'],
            "unsubscribed"  => 0
        ]);
        $this->assertDatabaseHas('new_user_brand', [
            "brand_id"     => $this->brand->id,
            "user_id"      => $user->id,
            "unsubscribed" => 0,
            "commercial_profile" => 1,
        ]);

        $newUserBrand = DB::table('new_user_brand')
        ->where('brand_id', $this->brand->id)
        ->where('user_id', $user->id)
        ->first();

        // Decode the subscriptions JSON field
        $subscriptions = json_decode($newUserBrand->subscriptions, true);

        // Verify the subscriptions array has the correct structure and contents
        $this->assertCount(2, $subscriptions);
        $this->assertEquals('notifications', $subscriptions[0]['type']);
        $this->assertEquals(1, $subscriptions[0]['subscribed']);
        $this->assertEquals('commercial_profile', $subscriptions[1]['type']);
        $this->assertEquals(1, $subscriptions[1]['subscribed']);

        $this->assertDatabaseHas('user_guid', [
            "id_usuario" => $user->id
        ]);
    }

    public function testCreateUnsubscribedUser()
    {
        $payload = $this->mountUserPayload([
            'unsubscribed' => 1,
            'commercial_profile' => 0
        ]);

        $this->json('POST', "/api/brands/" . $this->brand->id . "/users", ["data" => $payload])
            ->assertStatus(200);

        $this->assertDatabaseHas('users', [
            "email" => $payload["email"],
            "nombre" => $payload["first_name"] . ' ' . $payload["last_name"],
            "first_name" => $payload["first_name"],
            "last_name" => $payload["last_name"],
            "lang" => $payload["lang"],
            "sexo" => $payload["gender"],
            "fecha_nacimiento" => $payload["birthday"],
            "location" => $payload["locale"],
            "user_card" => $payload["document_number"],
            "email_result" => $payload["email_result"],
            "pais" => $payload["country"],
            "sendex" => $payload["sendex"],
            "unsubscribed" => 0,
            "generation" => $this->userService->getGeneration($payload["birthday"])
        ]);
        $user = User::where("email", $payload['email'])->first();

        $this->assertDatabaseHas('user_hotels', [
            "id_hotel"      => $this->hotel->id,
            "id_cadena"     => $this->chain->id,
            "id_usuario"    => $user->id,
            "user_hotel_id" => $payload['pms_id'],
            "unsubscribed"  => 1
        ]);
        $this->assertDatabaseHas('new_user_brand', [
            "brand_id"     => $this->brand->id,
            "user_id"      => $user->id,
            "unsubscribed" => 1,
            "commercial_profile" => 0
        ]);
        $this->assertDatabaseHas('user_guid', [
            "id_usuario" => $user->id
        ]);
    }

    public function testChildUsersAndRelatedTablesRowsAreCreated()
    {
        $payload = $this->mountUserPayload([
            'age' => 12,
        ]);

        $this->json('POST', "/api/brands/" . $this->brand->id . "/users", ["data" => $payload])
            ->assertStatus(200);

        $this->assertDatabaseHas('users', [
            "email" => $payload["email"],
            "nombre" => $payload["first_name"] . ' ' . $payload["last_name"],
            "first_name" => $payload["first_name"],
            "last_name" => $payload["last_name"],
            "lang" => $payload["lang"],
            "sexo" => $payload["gender"],
            "fecha_nacimiento" => $payload["birthday"],
            "location" => $payload["locale"],
            "user_card" => $payload["document_number"],
            "email_result" => $payload["email_result"],
            "pais" => $payload["country"],
            "sendex" => $payload["sendex"],
            "unsubscribed" => $payload["unsubscribed"],
            "generation" => $this->userService->getGeneration($payload["birthday"])
        ]);
        $user = User::where("email", $payload['email'])->first();

        $this->assertDatabaseHas('user_hotels', [
            "id_hotel"      => $this->hotel->id,
            "id_cadena"     => $this->chain->id,
            "id_usuario"    => $user->id,
            "user_hotel_id" => $payload['pms_id'],
            "unsubscribed"  => 0
        ]);
        $this->assertDatabaseHas('new_user_brand', [
            "brand_id"     => $this->brand->id,
            "user_id"      => $user->id,
            "unsubscribed" => 0,
            "commercial_profile" => 1
        ]);
        $this->assertDatabaseHas('user_guid', [
            "id_usuario" => $user->id
        ]);
    }

    public function testIncorrectUserPayloadDoesntPassValidation()
    {
        $this->withoutExceptionHandling();
        $payload = $this->mountUserPayload();
        $payload["birthday"] = "01/01/2000";
        $payload["email"] = "not_email";
        $payload["first_name"] = "A";
        $payload["last_name"] = "A";
        $payload["lang"] = null;
        $payload["locale"] = null;
        $payload["email_result"] = null;
        $payload["country"] = null;
        $payload["sendex"] = null;
        $payload["unsubscribed"] = null;
        $payload["customer"] = null;
        $payload["pms_id"] = null;

        try {
            $this->json('POST', "/api/brands/" . $this->brand->id . "/users", ["data" => $payload])
                ->assertStatus(400);
        } catch (InvalidRequestException $e) {
            // expectException() doesn't work here, so we assert it is the correct exception this way instead
            $this->assertStringContainsString(InvalidRequestException::class, $e);
            $this->assertStringContainsString("Bad fields in request body", $e);

            // Assert validation errors
            $this->assertEquals(
                [
                    "data.birthday"     => ["The data.birthday does not match the format Y-m-d."],
                    "data.email"        => ["The data.email must be a valid email address."],
                    "data.first_name"   => ["The data.first name must be at least 2 characters."],
                    "data.last_name"    => ["The data.last name must be at least 2 characters."],
                    "data.lang"         => ["The data.lang field is required."],
                    "data.locale"       => ["The data.locale field is required."],
                    "data.email_result" => ["The data.email result field is required."],
                    "data.country"      => ["The data.country field is required."],
                    "data.sendex"       => ["The data.sendex field is required."],
                    "data.unsubscribed" => ["The data.unsubscribed field is required."]
                ],
                $e->getDetail()
            );
        }
    }

    public function testCanCreateUserWithEnglishLocaleAndShortChineseNameCorrectly()
    {
        $payload = $this->mountUserPayload([
            'lang' => 'en',
            'first_name' => '漢',
        ]);
        $this->assertUserCreatedCorrectly($payload, '漢');
    }

    public function testCanCreateUserWithEnglishLocaleAndShortKoreanNameCorrectly()
    {
        $payload = $this->mountUserPayload([
            'lang' => 'en',
            'first_name' => '홍',
        ]);
        $this->assertUserCreatedCorrectly($payload, '홍');
    }

    public function testCanCreateUserWithEnglishLocaleAndShortJapaneseNameCorrectly()
    {
        $payload = $this->mountUserPayload([
            'lang' => 'en',
            'first_name' => 'ぁ',
        ]);
        $this->assertUserCreatedCorrectly($payload, 'ぁ');
    }

    public function testCanCreateUserWithChineseLocaleAndShortNameCorrectly()
    {
        $payload = $this->mountUserPayload([
            'lang' => 'zh',
            'first_name' => 'T',
        ]);
        $this->assertUserCreatedCorrectly($payload, 'T');
    }

    public function testCanCreateUserWithKoreanLocaleAndShortNameCorrectly()
    {
        $payload = $this->mountUserPayload([
            'lang' => 'ko',
            'first_name' => 'T',
        ]);
        $this->assertUserCreatedCorrectly($payload, 'T');
    }

    public function testCanCreateUserWithJapaneseLocaleAndShortNameCorrectly()
    {
        $payload = $this->mountUserPayload([
            'lang' => 'ja',
            'first_name' => 'T',
        ]);
        $this->assertUserCreatedCorrectly($payload, 'T');
    }

    public function testAdultUsersAreUpdatedCorrectly()
    {
        $payload = $this->mountUserPayload();

        // Create user
        $this->json('POST', "/api/brands/" . $this->brand->id . "/users", ["data" => $payload]);

        $user = User::where("email", $payload['email'])->first();
        $this->assertDatabaseHas('user_hotels', [
            "id_hotel"      => $this->hotel->id,
            "id_cadena"     => $this->chain->id,
            "id_usuario"    => $user->id,
            "user_hotel_id" => $payload['pms_id'],
            "unsubscribed"  => 0
        ]);

        // Mount payload to update user
        $unsubscribed = 1;
        $commercial_profile = 0;
        $new_payload = $this->mountUserPayload([
            'email' => $user->email,
            'unsubscribed' => $unsubscribed,
            'commercial_profile' => $commercial_profile
        ]);

        $new_payload['document_number'] = "87654321B";
        $new_payload['gender'] = "female";

        // Update user
        $this->json('POST', "/api/brands/" . $this->brand->id . "/users", ["data" => $new_payload])
            ->assertStatus(200);

        $this->assertDatabaseMissing('users', [
            "email" => $payload["email"],
            "nombre" => $payload["first_name"] . ' ' . $payload["last_name"],
            "first_name" => $payload["first_name"],
            "last_name" => $payload["last_name"],
            "lang" => $payload["lang"],
            "sexo" => $payload["gender"],
            "fecha_nacimiento" => $payload["birthday"],
            "location" => $payload["locale"],
            "user_card" => $payload["document_number"],
            "email_result" => $payload["email_result"],
            "pais" => $payload["country"],
            "sendex" => $payload["sendex"],
            "unsubscribed" => $payload["unsubscribed"],
            "generation" => $this->userService->getGeneration($payload["birthday"])
        ]);
        $this->assertDatabaseHas('users', [
            "email" => $new_payload["email"],
            "nombre" => $new_payload["first_name"] . ' ' . $new_payload["last_name"],
            "first_name" => $new_payload["first_name"],
            "last_name" => $new_payload["last_name"],
            "lang" => $new_payload["lang"],
            "sexo" => $new_payload["gender"],
            "fecha_nacimiento" => $new_payload["birthday"],
            "location" => $new_payload["locale"],
            "user_card" => $new_payload["document_number"],
            "email_result" => $new_payload["email_result"],
            "pais" => $new_payload["country"],
            "sendex" => $new_payload["sendex"],
            "unsubscribed" => $unsubscribed,
            "generation" => $this->userService->getGeneration($new_payload["birthday"])
        ]);
        $user = User::where("email", $payload['email'])->first();

        $this->assertDatabaseHas('user_hotels', [
            "id_hotel"      => $this->hotel->id,
            "id_cadena"     => $this->chain->id,
            "id_usuario"    => $user->id,
            "user_hotel_id" => $payload['pms_id'],
            "unsubscribed"  => $unsubscribed
        ]);

        $this->assertDatabaseHas('new_user_brand', [
            "brand_id"     => $this->brand->id,
            "user_id"      => $user->id,
            "unsubscribed" => $unsubscribed,
            "commercial_profile" => $commercial_profile,
        ]);

        // Verify new_user_brand table update with correct subscriptions
        $newUserBrand = DB::table('new_user_brand')
        ->where('brand_id', $this->brand->id)
        ->where('user_id', $user->id)
        ->first();

        $subscriptions = json_decode($newUserBrand->subscriptions, true);

        $this->assertCount(4, $subscriptions);
        $this->assertEquals('notifications', $subscriptions[0]['type']);
        $this->assertEquals(1, $subscriptions[0]['subscribed']);
        $this->assertEquals('commercial_profile', $subscriptions[1]['type']);
        $this->assertEquals(1, $subscriptions[1]['subscribed']);
        $this->assertEquals('notifications', $subscriptions[2]['type']);
        $this->assertEquals(0, $subscriptions[2]['subscribed']);
        $this->assertEquals('commercial_profile', $subscriptions[3]['type']);
        $this->assertEquals(0, $subscriptions[3]['subscribed']);
    }


    public function testChildUsersAreNotUpdated()
    {
        $payload = $this->mountUserPayload([
            'age' => 12,
        ]);

        // Create user
        $this->json('POST', "/api/brands/" . $this->brand->id . "/users", ["data" => $payload]);

        // Mount payload to update user
        $unsubscribed = 1;
        $new_payload = $this->mountUserPayload([
            'email' => $payload['email'],
            'age' => 12,
            'unsubscribed' => $unsubscribed
        ]);

        $new_payload['document_number'] = "87654321B";
        $new_payload['gender'] = "female";

        // Attempt to update user
        $this->json('POST', "/api/brands/" . $this->brand->id . "/users", ["data" => $new_payload])
            ->assertStatus(200);

        // Assert user has NOT been updated
        $this->assertDatabaseMissing('users', [
            "email" => $new_payload["email"],
            "nombre" => $new_payload["first_name"] . ' ' . $new_payload["last_name"],
            "first_name" => $new_payload["first_name"],
            "last_name" => $new_payload["last_name"],
            "lang" => $new_payload["lang"],
            "sexo" => $new_payload["gender"],
            "fecha_nacimiento" => $new_payload["birthday"],
            "location" => $new_payload["locale"],
            "user_card" => $new_payload["document_number"],
            "email_result" => $new_payload["email_result"],
            "pais" => $new_payload["country"],
            "sendex" => $new_payload["sendex"],
            "unsubscribed" => $payload["unsubscribed"], // For children we only update subscription status
            "generation" => $this->userService->getGeneration($new_payload["birthday"])
        ]);
        $this->assertDatabaseHas('users', [
            "email" => $payload["email"],
            "nombre" => $payload["first_name"] . ' ' . $payload["last_name"],
            "first_name" => $payload["first_name"],
            "last_name" => $payload["last_name"],
            "lang" => $payload["lang"],
            "sexo" => $payload["gender"],
            "fecha_nacimiento" => $payload["birthday"],
            "location" => $payload["locale"],
            "user_card" => $payload["document_number"],
            "email_result" => $payload["email_result"],
            "pais" => $payload["country"],
            "sendex" => $payload["sendex"],
            "unsubscribed" => $unsubscribed, // For children we only update subscription status
            "generation" => $this->userService->getGeneration($payload["birthday"])
        ]);
        $user = User::where("email", $payload['email'])->first();

        $this->assertDatabaseHas('user_hotels', [
            "id_hotel"      => $this->hotel->id,
            "id_cadena"     => $this->chain->id,
            "id_usuario"    => $user->id,
            "user_hotel_id" => $payload['pms_id'],
            "unsubscribed"  => $unsubscribed
        ]);

        $this->assertDatabaseHas('new_user_brand', [
            "brand_id"     => $this->brand->id,
            "user_id"      => $user->id,
            "unsubscribed" => $unsubscribed
        ]);
    }

    public function testUserCreatedAreUpdatedInHotelWithoutParent()
    {
        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', $hotel->id)->first();

        $this->assertNull($brand->parent);

        $payload = $this->mountUserPayload();

        // Create user
        $this->json('POST', "/api/brands/" . $brand->id . "/users", ["data" => $payload]);

        $user = User::where("email", $payload['email'])->first();
        $this->assertDatabaseHas('user_hotels', [
            "id_hotel"      => $hotel->id,
            "id_cadena"     => 0,
            "id_usuario"    => $user->id,
            "user_hotel_id" => $payload['pms_id'],
            "unsubscribed"  => 0
        ]);

        // Mount payload to update user
        $unsubscribed = 1;
        $commercial_profile = 0;
        $new_payload = $this->mountUserPayload([
            'email' => $user->email,
            'unsubscribed' => $unsubscribed,
            'commercial_profile' => $commercial_profile
        ]);

        $new_payload['document_number'] = "87654321B";
        $new_payload['gender'] = "female";

        // Update user
        $this->json('POST', "/api/brands/" . $brand->id . "/users", ["data" => $new_payload])
            ->assertStatus(200);

        $user = User::where("email", $new_payload['email'])->first();
        $this->assertDatabaseHas('user_hotels', [
            "id_hotel"      => $hotel->id,
            "id_cadena"     => 0,
            "id_usuario"    => $user->id,
            "user_hotel_id" => $payload['pms_id'],
            "unsubscribed"  => 1
        ]);
    }


    public function testUserCreatedEventIsEmitted()
    {
        $payload = $this->mountUserPayload();

        Event::fake();
        $this->json('POST', "/api/brands/" . $this->brand->id . "/users", ["data" => $payload]);

        Event::assertDispatched(UserCreatedEvent::class);
        Event::assertNotDispatched(UserUpdatedEvent::class);
    }

    public function testUserUpdatedEventIsEmitted()
    {
        factory(User::class)->create([
            'first_name' => 'Testname',
            'fecha_nacimiento' => '2000-01-01',
            'email' => 'testuser@email.com'
        ]);
        $payload = $this->mountUserPayload([
            'email' => 'testuser@email.com',
        ]);
        Event::fake();
        $this->json('POST', "/api/brands/" . $this->brand->id . "/users", ["data" => $payload]);

        Event::assertDispatched(UserUpdatedEvent::class);
        Event::assertNotDispatched(UserCreatedEvent::class);
    }

    public function testUsersAreShowCorrectly()
    {
        $user = factory(User::class)->create([
            'nombre' => 'First User'
        ]);

        $this->json('GET', '/api/users/' . $user->id, [])
            ->assertStatus(200)
            ->assertJson([
                'name' => 'First User'
            ]);
    }

    public function testUserByEmailAndBrandId()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', $hotel->id)->first();
        $user = factory(User::class)->create([
            'id'    => -1,
            'email' => 'x@domain.com'
        ]);
        $user_hotel = factory(UserHotel::class)->create([
            'id'         => -1,
            'id_usuario' => $user->id,
            'id_hotel'   => $hotel->id
        ]);

        $this->json('GET', '/api/users/' . $user->email . '/' . $brand->id)
            ->assertStatus(200)
            ->assertJson([
                'email' => $user->email
            ]);
    }

    public function testAdultIsAttachedCorrectlyToDifferentHotels()
    {
        $payload = $this->mountUserPayload();
        $this->json('POST', "/api/brands/" . $this->brand->id . "/users", ["data" => $payload])
            ->assertStatus(200);

        $user = User::where("email", $payload['email'])->first();

        $this->assertDatabaseHas('user_hotels', [
            "id_hotel"      => $this->hotel->id,
            "id_cadena"     => $this->chain->id,
            "id_usuario"    => $user->id,
            "user_hotel_id" => $payload['pms_id'],
            "unsubscribed"  => 0
        ]);

        $this->assertDatabaseHas('new_user_brand', [
            "brand_id"     => $this->brand->id,
            "user_id"      => $user->id,
            "unsubscribed" => 0
        ]);

        // Rerun setup to make a new brand
        $this->setUp();

        // Create user in the new brand
        $this->json('POST', "/api/brands/" . $this->brand->id . "/users", ["data" => $payload])
            ->assertStatus(200);

        $this->assertDatabaseHas('user_hotels', [
            "id_hotel"      => $this->hotel->id,
            "id_cadena"     => $this->chain->id,
            "id_usuario"    => $user->id,
            "user_hotel_id" => $payload['pms_id'],
            "unsubscribed"  => 0
        ]);

        $this->assertDatabaseHas('new_user_brand', [
            "brand_id"     => $this->brand->id,
            "user_id"      => $user->id,
            "unsubscribed" => 0
        ]);
    }

    public function testChildIsAttachedCorrectlyToDifferentHotels()
    {
        $payload = $this->mountUserPayload([
            'age' => 12,
        ]);

        $this->json('POST', "/api/brands/" . $this->brand->id . "/users", ["data" => $payload])
            ->assertStatus(200);

        $user = User::where("email", $payload['email'])->first();

        $this->assertDatabaseHas('user_hotels', [
            "id_hotel"      => $this->hotel->id,
            "id_cadena"     => $this->chain->id,
            "id_usuario"    => $user->id,
            "user_hotel_id" => $payload['pms_id'],
            "unsubscribed"  => 0
        ]);

        $this->assertDatabaseHas('new_user_brand', [
            "brand_id"     => $this->brand->id,
            "user_id"      => $user->id,
            "unsubscribed" => 0
        ]);

        // Rerun setup to make a new brand
        $this->setUp();

        // Create user in the new brand
        $this->json('POST', "/api/brands/" . $this->brand->id . "/users", ["data" => $payload])
            ->assertStatus(200);

        $this->assertDatabaseHas('user_hotels', [
            "id_hotel"      => $this->hotel->id,
            "id_cadena"     => $this->chain->id,
            "id_usuario"    => $user->id,
            "user_hotel_id" => $payload['pms_id'],
            "unsubscribed"  => 0
        ]);

        $this->assertDatabaseHas('new_user_brand', [
            "brand_id"     => $this->brand->id,
            "user_id"      => $user->id,
            "unsubscribed" => 0
        ]);
    }

    public function testGetGenerationReturnsExpectedValue()
    {
        $generation = $this->userService->getGeneration('1927-01-01');
        $this->assertEquals('mature', $generation);
        $generation = $this->userService->getGeneration('1935-01-01');
        $this->assertEquals('mature', $generation);

        $generation = $this->userService->getGeneration('1946-01-01');
        $this->assertEquals('baby boomer', $generation);
        $generation = $this->userService->getGeneration('1955-01-01');
        $this->assertEquals('baby boomer', $generation);

        $generation = $this->userService->getGeneration('1965-01-01');
        $this->assertEquals('generation x', $generation);
        $generation = $this->userService->getGeneration('1975-01-01');
        $this->assertEquals('generation x', $generation);

        $generation = $this->userService->getGeneration('1981-01-01');
        $this->assertEquals('millenial', $generation);
        $generation = $this->userService->getGeneration('1995-01-01');
        $this->assertEquals('millenial', $generation);

        $generation = $this->userService->getGeneration('2001-01-01');
        $this->assertEquals('generation z', $generation);
        $generation = $this->userService->getGeneration('2015-01-01');
        $this->assertEquals('generation z', $generation);
    }

    public function testInvalidValuesAreCleaned()
    {
        $payload = $this->mountUserPayload();
        $payload['email'] = "email'<br>@email.com";
        $payload['first_name'] = "first'_<br>name";
        $payload['last_name'] = "last'_<br>name";
        $payload['lang'] = "en'<br>";
        $payload['gender'] = "male'<br>";
        $payload['birthday'] = "2000-01-01<br>";
        $payload['locale'] = "en_US'<br>";
        $payload['document_number'] = "12345678A'<br>";
        $payload['country'] = "Spain'<br>";
        $payload['pms_id'] = "ASD'FG'<br>";
        $payload['facebook_img'] = "http://www.fakeurl.com/<br>fakepath";

        $this->json('POST', "/api/brands/" . $this->brand->id . "/users", ["data" => $payload])
            ->assertStatus(200);

        $this->assertDatabaseHas('users', [
            "email" => "email'@email.com",
            "first_name" => "first'_name",
            "last_name" => "last'_name",
            "lang" => "en'",
            "sexo" => "male'",
            "fecha_nacimiento" => "2000-01-01",
            "location" => "en_US'",
            "user_card" => "12345678A'",
            "pais" => "Spain'"
        ]);
        $this->assertDatabaseHas('user_hotels', ["user_hotel_id" => "ASD'FG'"]);
    }

    private function assertUserCreatedCorrectly($payload, $firstName)
    {
        $this->json('POST', "/api/brands/" . $this->brand->id . "/users", ["data" => $payload])->assertStatus(200);

        $this->assertDatabaseHas('users', [
            "email" => $payload["email"],
            "nombre" => $firstName . ' ' . $payload["last_name"],
            "first_name" => $firstName,
            "last_name" => $payload["last_name"],
            "lang" => $payload["lang"],
            "sexo" => $payload["gender"],
            "fecha_nacimiento" => $payload["birthday"],
            "location" => $payload["locale"],
            "user_card" => $payload["document_number"],
            "email_result" => $payload["email_result"],
            "pais" => $payload["country"],
            "sendex" => $payload["sendex"],
            "unsubscribed" => $payload["unsubscribed"],
            "generation" => $this->userService->getGeneration($payload["birthday"])
        ]);
        $user = User::where("email", $payload['email'])->first();

        $this->assertDatabaseHas('user_hotels', [
            "id_hotel"      => $this->hotel->id,
            "id_cadena"     => $this->chain->id,
            "id_usuario"    => $user->id,
            "user_hotel_id" => $payload['pms_id'],
            "unsubscribed"  => 0
        ]);
        $this->assertDatabaseHas('new_user_brand', [
            "brand_id"     => $this->brand->id,
            "user_id"      => $user->id,
            "unsubscribed" => 0,
            "commercial_profile" => 1
        ]);
        $this->assertDatabaseHas('user_guid', [
            "id_usuario" => $user->id
        ]);
    }

    public function testUniqueUserPerAccount()
    {
        // Test that a user is generate in both brands with same data and same user_id
        $payload = $this->mountUserPayload();

        [$user1, $user2] = $this->createUsers($payload);
        $brandIds = [$user1->brand_id, $user2->brand_id];

        $this->assertEquals(2, UserBrand::whereIn('brand_id', $brandIds)->get()->count());

        $this->assertEquals(
            Arr::first($brandIds),
            UserBrand::whereIn('brand_id', $brandIds)->first()->brand_id
        );

        $this->assertNotEquals(
            UserBrand::whereIn('brand_id', $brandIds)->first()->brand_id,
            UserBrand::whereIn('brand_id', $brandIds)->get()->last()->brand_id
        );

        $generation = $this->userService->getGeneration($user1->user_birthdate);

        $expectedUserData = json_decode($this->expectedUserData($payload), true);

        $expectedUserData['generation'] = $generation;
        $expectedUserData = json_encode($expectedUserData);

        $this->assertJsonStringEqualsJsonString(
            $expectedUserData,
            $user1->user_data
        );

        $this->assertJsonStringEqualsJsonString(
            UserBrand::whereIn('brand_id', $brandIds)->first()->user_data,
            UserBrand::whereIn('brand_id', $brandIds)->get()->last()->user_data
        );

        // Test that user is generated in 3rd brand wich is the same parent, with same userId and same userData, and the data is updated in all brands
        $this->generateBrandInSameParent();
        $payload = $this->mountUserPayload([], $payload['email']);

        $brandIds = $this->brand->where(
            'parent_id',
            $this->brand->parent_id
        )->get()->pluck('id');

        [$user3] = $this->createUsers($payload, collect($brandIds->last()));

        $user1 = $this->userService->getUserBrands([$user1->brand_id], $user1->user_id)->first();

        $this->assertEquals(
            3,
            UserBrand::whereIn('brand_id', $brandIds)->get()->count()
        );

        $this->assertEquals(
            $user3->user_id,
            $user1->user_id
        );


        $expectedUserData = json_decode($this->expectedUserData($payload), true);

        $expectedUserData['generation'] = $generation;
        $expectedUserData = json_encode($expectedUserData);


        $this->assertJsonStringEqualsJsonString(
            $expectedUserData,
            $user3->user_data
        );

        $this->assertJsonStringEqualsJsonString(
            $user3->user_data,
            $user1->user_data
        );

        // Test same user is created in 4th brand that is not the same parent, thus the data is not updated
        $hotel = factory(Hotel::class)->create();
        $chain = factory(Cadena::class)->create();
        factory(CadenaHotel::class)->create([
            'id_cadena' => $chain->id,
            'id_hotel'  => $hotel->id
        ]);
        $brand2 = Brand::where('hotel_id', $hotel->id)->first();

        $payload = $this->mountUserPayload([], $payload['email']);

        [$user4]  = $this->createUsers($payload, collect($brand2->id));

        // Fetch again user1 to ensure we0ve the last data
        $user1 = $this->userService->getUserBrands([$user1->brand_id], $user1->user_id)->first();

        $this->assertEquals(
            1,
            UserBrand::where('brand_id', $brand2->id)->get()->count()
        );

        $this->assertEquals(
            $user4->user_id,
            $user1->user_id
        );

        $this->assertNotEquals(
            Arr::get(json_decode($user4->user_data, true), 'first_name'),
            Arr::get(json_decode($user1->user_data, true), 'first_name')
        );
    }

    public function testUnsubscribe()
    {
        [$user1, $user2] = $this->createUsers();
        $this->assertEquals(0, $user1->unsubscribed);

        $this->json('PUT', "/api/brands/$user1->brand_id/users/$user1->user_id/unsubscribe", [
            "unsubscribe_notifications" => true,
            "unsubscribe_commercial_profile" => true
        ])->assertStatus(200);
        [$user1, $user2] = $this->userService->getUserBrands([$user1->brand_id, $user2->brand_id], $user1->user_id);

        $this->assertEquals(1, $user2->unsubscribed);
        $this->assertJsonStringEqualsJsonString($user1->subscriptions, $user2->subscriptions);
    }

    public function testUnsubscribeDoesntAffectOtherBrands()
    {
        [$user1] = $this->createUsers();

        $this->assertEquals(0, $user1->unsubscribed);

        $hotel = factory(Hotel::class)->create();
        $chain = factory(Cadena::class)->create();
        factory(CadenaHotel::class)->create([
            'id_cadena' => $chain->id,
            'id_hotel'  => $hotel->id
        ]);

        $brand2 = Brand::where('hotel_id', $hotel->id)->first();

        [$unsubscribedUser] = $this->createUsers(null, collect($brand2->id));

        $this->json('PUT', "/api/brands/" . $unsubscribedUser->brand_id . "/users/" . $unsubscribedUser->user_id . "/unsubscribe", [
            "unsubscribe_notifications" => true,
            "unsubscribe_commercial_profile" => true
        ])->assertStatus(200);

        $unsubscribedUser = $this->userService->getUserBrands([$unsubscribedUser->brand_id], $unsubscribedUser->user_id)->first();

        // First user is unsubscribed as is the one that we did the request, second one is not changed
        $this->assertEquals(
            1,
            $unsubscribedUser->unsubscribed
        );
        $this->assertEquals(
            0,
            $user1->unsubscribed
        );
    }

    private function generateBrandInSameParent()
    {
        $hotel = factory(Hotel::class)->create();
        factory(CadenaHotel::class)->create([
            'id_cadena' => $this->chain->id,
            'id_hotel'  => $hotel->id
        ]);
        Brand::where('hotel_id', $this->hotel->id)->first();
    }

    private function expectedUserData($user)
    {
        return json_encode([
            "name" => Arr::get($user, 'first_name') . " " . Arr::get($user, 'last_name'),
            "first_name" => Arr::get($user, 'first_name'),
            "last_name" => Arr::get($user, 'last_name'),
            "email" => Arr::get($user, 'email'),
            "gender" => Arr::get($user, 'gender'),
            "birthdate" => Arr::get($user, 'birthday'),
            "phone_number" => Arr::get($user, 'phone_number'),
            "generation" => Arr::get($user, 'generation'),
            "lang" => Arr::get($user, 'lang'),
            "country" => Arr::get($user, 'country'),
        ]);
    }


    private function createUsers($payload = null, $brandIds = null)
    {

        $this->generateBrandInSameParent();
        $brandIds = $brandIds ?? $this->brand->where('parent_id', $this->brand->parent_id)->get()->pluck('id');

        $payload = $payload ?? $this->mountUserPayload();
        return $brandIds->map(function ($brandId) use ($payload) {
            $response =  $this->json('POST', "/api/brands/" . $brandId . "/users", ["data" => $payload]);
            $response->assertStatus(200);

            return UserBrand::where(['brand_id' =>  $brandId,
            'user_id' => $response->json('user.id')])->first();
        });
    }

    public function testUpdateGenerationViaEvent()
    {

        $faker = Factory::create();

        $userBrand = $this->createUsers()->first();

        $generation = $this->userService->getGeneration($userBrand->user_birthdate);

        $user = $this->userService->getUserBrands([$userBrand->brand_id], $userBrand->user_id)->first();

        $this->assertEquals(Arr::get(json_decode($user->user_data, true), 'generation'), $generation);

        $newBirthdate = "$faker->date";

        $generation = $this->userService->getGeneration($newBirthdate);



        $payload = [
            "payload" => [
                "brand" => [
                    "id" => $userBrand->brand_id
                ],
                "user" => [
                    "id" => $userBrand->user_id,
                    "birthDate" => $newBirthdate
                ]
            ]
                ];

        event(new UserCreated($payload, app(ApiGatewayConnection::class)));

        $user = $this->userService->getUserBrands([$userBrand->brand_id], $userBrand->user_id)->first();
        $this->assertEquals(Arr::get(json_decode($user->user_data, true), 'generation'), $generation);
    }
}
