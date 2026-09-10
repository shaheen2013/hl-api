<?php

namespace Tests\Feature;

use App\BookingFunnel;
use App\Cadena;
use App\Hotel;
use App\CadenaHotel;
use App\SocialMedia;
use App\SocialMediaShare;
use App\User;
use App\Brand;
use App\UserBrand;
use App\Visit;
use App\Lang;
use App\Device;
use App\Connection;
use App\AccessType;
use App\QuestionBrand;
use App\Satisfaction;
use App\Survey;
use App\UserSurvey;
use App\SurveyQuestion;
use App\UserSurveyQuestionAnswer;
use Carbon\Carbon;
use Tests\TestCase;
use Log;

class ClientControllerTest extends TestCase
{
    public function testGetClientsSuccessfully()
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
        $brandChain = Brand::where('hotel_id', '=', $hotel2->id)->first();
        $userHosted = factory(User::class)->create();
        $userNotHosted = factory(User::class)->create();
        $userSubscribed = factory(User::class)->create();
        $userChain = factory(User::class)->create();

        $userBrandHosted = factory(UserBrand::class)->create([
            'brand_id'      => $brand->id,
            'user_id'       => $userHosted->id,
            'date'          => Carbon::yesterday()->setTimezone('UTC'),
            'unsubscribed'  => 1,
            'user_data'     => json_encode([
                "name"          => $userHosted->name,
                "first_name"    => $userHosted->first_name,
                "last_name"     => $userHosted->last_name,
                "email"         => $userHosted->email,
                "gender"        => $userHosted->gender,
                "birthdate"     => $userHosted->birthday,
                "phone_number"  => data_get($userHosted->data, 'phone_number'),
                "generation"    => $userHosted->generation,
                "lang"          =>  $userHosted->lang,
                "country"       => $userHosted->country,
            ])
        ]);

        $userBrandNotHosted = factory(UserBrand::class)->create([
            'brand_id'  => $brand->id,
            'user_id'   => $userNotHosted->id,
            'date'      => Carbon::now()->subMonths(1)->setTimezone('UTC'),
            'unsubscribed'  => 1,
            'user_data'     => json_encode([
                "name"          => $userNotHosted->name,
                "first_name"    => $userNotHosted->first_name,
                "last_name"     => $userNotHosted->last_name,
                "email"         => $userNotHosted->email,
                "gender"        => $userNotHosted->gender,
                "birthdate"     => $userNotHosted->birthday,
                "phone_number"  => data_get($userNotHosted->data, 'phone_number'),
                "generation"    => $userNotHosted->generation,
                "lang"          =>  $userNotHosted->lang,
                "country"       => $userNotHosted->country,
            ])
        ]);

        $userBrandChain = factory(UserBrand::class)->create([
            'brand_id'      => $brandChain->id,
            'user_id'       => $userChain->id,
            'date'          => Carbon::now()->subYears(1)->setTimezone('UTC'),
            'unsubscribed'  => 1,
            'user_data'     => json_encode([
                "name"          => $userChain->name,
                "first_name"    => $userChain->first_name,
                "last_name"     => $userChain->last_name,
                "email"         => $userChain->email,
                "gender"        => $userChain->gender,
                "birthdate"     => $userChain->birthday,
                "phone_number"  => data_get($userChain->data, 'phone_number'),
                "generation"    => $userChain->generation,
                "lang"          =>  $userChain->lang,
                "country"       => $userChain->country,
            ])
        ]);


        $userBrandSubscribed = factory(UserBrand::class)->create([
            'brand_id'          => $brand->id,
            'user_id'           => $userSubscribed->id,
            'date'              => Carbon::now()->subDays(3)->setTimezone('UTC'),
            'unsubscribed'      => 0,
            'user_data'     => json_encode([
                "name"          => $userSubscribed->name,
                "first_name"    => $userSubscribed->first_name,
                "last_name"     => $userSubscribed->last_name,
                "email"         => $userSubscribed->email,
                "gender"        => $userSubscribed->gender,
                "birthdate"     => $userSubscribed->birthday,
                "phone_number"  => data_get($userSubscribed->data, 'phone_number'),
                "generation"    => $userSubscribed->generation,
                "lang"          =>  $userSubscribed->lang,
                "country"       => $userSubscribed->country,
            ])
        ]);

        $visitHosted = factory(Visit::class)->create([
            'user_brand_id' => $userBrandHosted->id,
            'brand_id'      => $userBrandHosted->brand_id,
            'user_id'       => $userBrandHosted->user_id,
            'check_out'     => Carbon::now()->addDays(5)->setTimezone('UTC'),
            'reservation'    => json_encode([
                "city" => null,
                "gender" => "male",
                "pms_id" => "2022_518_1_1",
                "res_id" => "2022_518_1",
                "address" => null,
                "birthday" => "1999-05-11",
                "brand_id" => 100,
                "check_in" => "2022-03-20",
                "hotel_id" => 51,
                "pax_type" => "AD",
                "province" => null,
                "res_data" => null,
                "res_date" => "2022-01-21",
                "check_out" => Carbon::now()->addDays(5)->setTimezone('UTC'),
                "last_name" => "Petzold Carsten",
                "res_board" => "HD",
                "telephone" => null,
                "first_name" => ".",
                "res_adults" => 2,
                "res_agency" => "SIDETOURS",
                "res_amount" => 607,
                "res_babies" => 0,
                "res_extras" => null,
                "res_nights" => 7,
                "document_id" => null,
                "nationality" => null,
                "postal_code" => null,
                "res_channel" => "SIDETOURS ON LINE",
                "res_company" => null,
                "res_juniors" => null,
                "res_seniors" => null,
                "res_children" => 0,
                "res_comments" => null,
                "res_contract" => null,
                "res_currency" => "EUR",
                "birth_country" => null,
                "res_room_type" => "DO",
                "res_room_number" => "102",
                "res_intermediary" => null,
                "residence_country" => null
            ])
        ]);
        factory(Visit::class)->create([
            'user_brand_id' => $userBrandNotHosted->id,
            'brand_id'      => $userBrandNotHosted->brand_id,
            'user_id'       => $userBrandNotHosted->user_id,
            'check_out'     => Carbon::now()->subMonths(1)->setTimezone('UTC')
        ]);
        factory(Visit::class)->create([
            'user_brand_id' => $userBrandChain->id,
            'brand_id'      => $userBrandChain->brand_id,
            'user_id'       => $userBrandChain->user_id,
            'check_out'     => Carbon::now()->subYears(1)->setTimezone('UTC')
        ]);
        factory(Visit::class)->create([
            'user_brand_id' => $userBrandSubscribed->id,
            'brand_id'      => $userBrandSubscribed->brand_id,
            'user_id'       => $userBrandSubscribed->user_id,
            'check_out'     => Carbon::now()->addDays(5)->setTimezone('UTC')
        ]);

        // Test get All Clients
        $this->json('GET', "/api/brands/$brand->id/clients")
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "user_id"       => $userSubscribed->id,
                        "client_name"   => $userSubscribed->nombre,
                        "client_email"  => $userSubscribed->email,
                        "brand_name"    => null,
                        "first_login"   => $userBrandSubscribed->date->setTimezone($hotel1->timeZone->time_zone)->format('Y-m-d H:i:s'),
                        'check_in'      => null,
                        'check_out'     => null,
                        'channel'       => null,
                        'agency'        => null,
                        "unsubscribed"  => $userBrandSubscribed->unsubscribed
                    ],
                    [
                        "user_id"       => $userNotHosted->id,
                        "client_name"   => $userNotHosted->nombre,
                        "client_email"  => $userNotHosted->email,
                        "brand_name"    => null,
                        "first_login"   => $userBrandNotHosted->date->setTimezone($hotel1->timeZone->time_zone)->format('Y-m-d H:i:s'),
                        'check_in'      => null,
                        'check_out'     => null,
                        'channel'       => null,
                        'agency'        => null,
                        "unsubscribed"  => $userBrandNotHosted->unsubscribed
                    ],
                    [
                        "user_id"       => $userHosted->id,
                        "client_name"   => $userHosted->nombre,
                        "client_email"  => $userHosted->email,
                        "brand_name"    => null,
                        "first_login"   => $userBrandHosted->date->setTimezone($hotel1->timeZone->time_zone)->format('Y-m-d H:i:s'),
                        'check_in'      => json_decode($visitHosted->reservation)->check_in,
                        'check_out'     => json_decode($visitHosted->reservation)->check_out,
                        'channel'       => json_decode($visitHosted->reservation)->res_channel,
                        'agency'        => json_decode($visitHosted->reservation)->res_agency,
                        "unsubscribed"  => $userBrandHosted->unsubscribed
                    ],
                ]
            ]);

        // Test get Hosted
        $this->json('GET', "/api/brands/$brand->id/clients", ["hosted" => 1])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "user_id"       => $userSubscribed->id,
                        "client_name"   => $userSubscribed->nombre,
                        "client_email"  => $userSubscribed->email,
                        "brand_name"    => null,
                        "first_login"   => $userBrandSubscribed->date->setTimezone($hotel1->timeZone->time_zone)->format('Y-m-d H:i:s'),
                        'check_in'      => null,
                        'check_out'     => null,
                        'channel'       => null,
                        'agency'        => null,
                        "unsubscribed"  => $userBrandSubscribed->unsubscribed
                    ],
                    [
                        "user_id"       => $userHosted->id,
                        "client_name"   => $userHosted->nombre,
                        "client_email"  => $userHosted->email,
                        "brand_name"    => null,
                        "first_login"   => $userBrandHosted->date->setTimezone($hotel1->timeZone->time_zone)->format('Y-m-d H:i:s'),
                        'check_in'      => json_decode($visitHosted->reservation)->check_in,
                        'check_out'     => json_decode($visitHosted->reservation)->check_out,
                        'channel'       => json_decode($visitHosted->reservation)->res_channel,
                        'agency'        => json_decode($visitHosted->reservation)->res_agency,
                        "unsubscribed"  => $userBrandHosted->unsubscribed
                    ],
                ]
            ]);

        // Test get Subscribed
        $this->json('GET', "/api/brands/$brand->id/clients", ["subscribed" => 1])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "user_id"       => $userSubscribed->id,
                        "client_name"   => $userSubscribed->nombre,
                        "client_email"  => $userSubscribed->email,
                        "brand_name"    => null,
                        "first_login"   => $userBrandSubscribed->date->setTimezone($hotel1->timeZone->time_zone)->format('Y-m-d H:i:s'),
                        'check_in'      => null,
                        'check_out'     => null,
                        'channel'       => null,
                        'agency'        => null,
                        "unsubscribed"  => $userBrandSubscribed->unsubscribed
                    ]
                ]
            ]);

        // Test get filter by dates
        $this->json(
            'GET',
            "/api/brands/$brand->id/clients",
            [
                "from" => Carbon::now()->subMonths(2)->format('Y-m-d H:i:s'),
                "to" => Carbon::now()->subDays(10)->format('Y-m-d H:i:s')
            ]
        )
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "user_id"       => $userNotHosted->id,
                        "client_name"   => $userNotHosted->nombre,
                        "client_email"  => $userNotHosted->email,
                        "brand_name"    => null,
                        "first_login"   => $userBrandNotHosted->date->setTimezone($hotel1->timeZone->time_zone)->format('Y-m-d H:i:s'),
                        'check_in'      => null,
                        'check_out'     => null,
                        'channel'       => null,
                        'agency'        => null,
                        "unsubscribed"  => $userBrandNotHosted->unsubscribed
                    ]
                ]
            ]);

        // Test get by chain
        $this->json('GET', "/api/brands/$brand->parent_id/clients")
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "user_id"       => $userChain->id,
                        "client_name"   => $userChain->nombre,
                        "client_email"  => $userChain->email,
                        "brand_name"    => $hotel2->hotelName,
                        "first_login"   => $userBrandChain->date->setTimezone($hotel1->timeZone->time_zone)->format('Y-m-d H:i:s'),
                        'check_in'      => null,
                        'check_out'     => null,
                        'channel'       => null,
                        'agency'        => null,
                        "unsubscribed"  => $userBrandChain->unsubscribed
                    ],
                    [
                        "user_id"       => $userSubscribed->id,
                        "client_name"   => $userSubscribed->nombre,
                        "client_email"  => $userSubscribed->email,
                        "brand_name"    => $hotel1->hotelName,
                        "first_login"   => $userBrandSubscribed->date->setTimezone($hotel1->timeZone->time_zone)->format('Y-m-d H:i:s'),
                        'check_in'      => null,
                        'check_out'     => null,
                        'channel'       => null,
                        'agency'        => null,
                        "unsubscribed"  => $userBrandSubscribed->unsubscribed
                    ],
                    [
                        "user_id"       => $userNotHosted->id,
                        "client_name"   => $userNotHosted->nombre,
                        "client_email"  => $userNotHosted->email,
                        "brand_name"    => $hotel1->hotelName,
                        "first_login"   => $userBrandNotHosted->date->setTimezone($hotel1->timeZone->time_zone)->format('Y-m-d H:i:s'),
                        'check_in'      => null,
                        'check_out'     => null,
                        'channel'       => null,
                        'agency'        => null,
                        "unsubscribed"  => $userBrandNotHosted->unsubscribed
                    ],
                    [
                        "user_id"       => $userHosted->id,
                        "client_name"   => $userHosted->nombre,
                        "client_email"  => $userHosted->email,
                        "brand_name"    => $hotel1->hotelName,
                        "first_login"   => $userBrandHosted->date->setTimezone($hotel1->timeZone->time_zone)->format('Y-m-d H:i:s'),
                        'check_in'      => json_decode($visitHosted->reservation)->check_in,
                        'check_out'     => json_decode($visitHosted->reservation)->check_out,
                        'channel'       => json_decode($visitHosted->reservation)->res_channel,
                        'agency'        => json_decode($visitHosted->reservation)->res_agency,
                        "unsubscribed"  => $userBrandHosted->unsubscribed
                    ],
                ]
            ]);
    }

    public function testGetClientsWithInvalidParams()
    {
        $this->json('GET', "/api/brands/1/clients", ['sort_order' => 'invalid'])->assertStatus(400);
    }

    public function testQueueReportSuccessfully()
    {
        $this->json('POST', "/api/brands/1/clients/report", ['emails' => ['i.coll@hotelinking.com']])->assertStatus(202);
    }

    public function testQueueReportWithInvalidParams()
    {
        $this->json('POST', "/api/brands/1/clients/report", ['emails' => ['invalidEmail']])->assertStatus(400);
    }

    public function testDeleteClientBrand()
    {
        $hotel = factory(Hotel::class)->create();
        $user = factory(User::class)->create();
        $userId = $user->id;
        $brandId = $hotel->brand->id;
        $bookingFunnel = factory(BookingFunnel::class)
            ->create([
                'user_id'  => $userId,
                'brand_id' => $brandId
            ]);

        $userBrand = factory(UserBrand::class)
            ->create([
                'brand_id' => $brandId,
                'user_id'  => $userId
            ]);

        $mediaShare = SocialMedia::inRandomOrder()->first();
        $userSocialMediaShare = factory(SocialMediaShare::class)
            ->create([
                'user_brand_id' => $userBrand->id,
                'share_type_id' => $mediaShare->id
            ]);

        $visit = factory(Visit::class)
            ->create([
                'user_brand_id' => $userBrand->id,
                'brand_id'      => $userBrand->brand_id,
                'user_id'       => $userBrand->user_id,
            ]);

        $this->json('DELETE', "/api/brands/{$brandId}/clients/{$userId}")
            ->assertStatus(200);

        $userSocialMediaShare = SocialMediaShare::find($userSocialMediaShare->id);
        $userBrand = UserBrand::find($userBrand->id);
        $bookingFunnel = BookingFunnel::find($bookingFunnel->id);
        $visit = Visit::find($visit->id);

        $this->assertEmpty($userSocialMediaShare);
        $this->assertEmpty($userBrand);
        $this->assertEmpty($bookingFunnel);
        $this->assertEmpty($visit);
    }

    public function testDeleteClientBrandChildren()
    {
        $hotel = factory(Hotel::class)->create();
        $this->createChain([$hotel]);

        $user = factory(User::class)->create();
        $userId = $user->id;
        $brandId = $hotel->brand->id;
        $parentBrandId = $hotel->brand->parent_id;

        $bookingFunnel = factory(BookingFunnel::class)
            ->create([
                'user_id'  => $userId,
                'brand_id' => $brandId
            ]);

        $userBrand = factory(UserBrand::class)
            ->create([
                'brand_id' => $brandId,
                'user_id'  => $userId
            ]);

        $mediaShare = SocialMedia::inRandomOrder()->first();
        $userSocialMediaShare = factory(SocialMediaShare::class)
            ->create([
                'user_brand_id' => $userBrand->id,
                'share_type_id' => $mediaShare->id
            ]);

        $visit = factory(Visit::class)
            ->create([
                'user_brand_id' => $userBrand->id,
                'brand_id'      => $userBrand->brand_id,
                'user_id'       => $userBrand->user_id,
            ]);

        $this->json('DELETE', "/api/brands/{$parentBrandId}/clients/{$userId}")
            ->assertStatus(200);

        $userSocialMediaShare = SocialMediaShare::find($userSocialMediaShare->id);
        $userBrand = UserBrand::find($userBrand->id);
        $bookingFunnel = BookingFunnel::find($bookingFunnel->id);
        $visit = Visit::find($visit->id);

        $this->assertEmpty($userSocialMediaShare);
        $this->assertEmpty($userBrand);
        $this->assertEmpty($bookingFunnel);
        $this->assertEmpty($visit);
    }

    public function testGetClientDetailSuccessfully()
    {
        $hotel1 = factory(Hotel::class)->create();
        $hotel2 = factory(Hotel::class)->create();
        $this->createChain([$hotel1, $hotel2]);

        $brand = Brand::where('hotel_id', '=', $hotel1->id)->first();
        $brandChain = Brand::where('hotel_id', '=', $hotel2->id)->first();
        $user = factory(User::class)->create();
        
        $userBrand = $this->createUserBrand($brand->id, $user);
        $userBrandChain = $this->createUserBrand($brandChain->id, $user);

        $visit = factory(Visit::class)->states('withReservation')->create([
            'user_brand_id' => $userBrand->id,
            'brand_id'      => $userBrand->brand_id,
            'user_id'       => $userBrand->user_id,
            'check_in'      => Carbon::now()->subDays(30)->setTimezone('UTC'),
            'check_out'     => Carbon::now()->subDays(25)->setTimezone('UTC'),
        ]);
        $brandVisitOverlapped = $this->createVisit($userBrandChain, $visit->check_in, $visit->check_out);
        $brandVisitNotOverlapped = $this->createVisit($userBrandChain, Carbon::now()->subDays(5)->setTimezone('UTC'), Carbon::now()->addDays(5)->setTimezone('UTC'));
        
        $device = factory(Device::class)->create();
        $connection = factory(Connection::class)->create([
            'brand_id'  => $brand->id,
            'visit_id'  => $visit->id,
            'device_id' => $device->id
        ]);

        $userSurveyQuestionAnswer = $this->createSurveyAnswer($hotel1->id, $user->id, $brand->id, $visit->check_in);

        $lang = Lang::where(['lang' => $user->lang])->first();
        $source = AccessType::where(['id' => $connection->access_type_id])->first();
        $this->json('GET', "/api/brands/$brand->id/clients/$user->id")
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        'id'                => $user->id,
                        'user_brand_id'     => $userBrand->id,
                        'name'              => $user->nombre,
                        'email'             => $user->email,
                        'country'           => $user->country,
                        'location'          => null,
                        'birthday'          => $user->fecha_nacimiento,
                        'gender'            => $user->sexo,
                        'card'              => $user->user_card,
                        'unsubscribed'      => $userBrand->unsubscribed,
                        'image'             => null,
                        'link'              => null,
                        'friends'           => null,
                        'brand_id'          => $brand->id,
                        'brand_name'        => $hotel1->hotelName,
                        'property_visits'   => 1,
                        'chain_visits'      => 2,
                        'visits'            => [
                            [
                                "id" => $visit->id,
                                "user_brand_id" => $userBrand->id,
                                "pms_validated" => true,
                                "pms_id" => json_decode($visit->reservation)->pms_id,
                                "reservation_id" => json_decode($visit->reservation)->res_id,
                                "check_in" => Carbon::parse($visit->check_in)->format('Y-m-d'),
                                "check_out" => Carbon::parse($visit->check_out)->format('Y-m-d'),
                                "channel" => json_decode($visit->reservation)->res_channel,
                                "agency" => json_decode($visit->reservation)->res_agency,
                                "telephone" => json_decode($visit->reservation)->telephone,
                                "is_client" => $visit->is_client,
                                "satisfaction" => [
                                    "answer" => $userSurveyQuestionAnswer->answer,
                                    "comment" => $userSurveyQuestionAnswer->comment,
                                    "created_at" => Carbon::parse($userSurveyQuestionAnswer->created_at)->format('Y-m-d H:i:s'),
                                ],
                                "connections" => [
                                    [
                                        "id"                        => $connection->id,
                                        "access_code"               => $connection->access_code,
                                        "mac_address"               => $device->mac_address,
                                        "device_family"             => $device->device_family,
                                        "operating_system"          => $connection->operating_system,
                                        "operating_system_version"  => (string) $connection->operating_system_version,
                                        "source"                    => $source->name,
                                        "created_at"                => Carbon::createFromFormat('Y-m-d H:i:s', $connection->created_at, 'UTC')->setTimezone($hotel1->timeZone->time_zone)->toDateTimeString()
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'id'                => $user->id,
                        'user_brand_id'     => $userBrandChain->id,
                        'name'              => $user->nombre,
                        'email'             => $user->email,
                        'country'           => $user->country,
                        'location'          => null,
                        'birthday'          => $user->fecha_nacimiento,
                        'gender'            => $user->sexo,
                        'card'              => $user->user_card,
                        'unsubscribed'      => $userBrandChain->unsubscribed,
                        'image'             => null,
                        'link'              => null,
                        'friends'           => null,
                        'brand_id'          => $brandChain->id,
                        'brand_name'        => $hotel2->hotelName,
                        'property_visits'   => 1,
                        'chain_visits'      => 2,
                        'visits'            => [
                            [
                                "id" => $brandVisitOverlapped->id,
                                "user_brand_id" => $userBrandChain->id,
                                "pms_validated" => false,
                                "pms_id" => null,
                                "reservation_id" => null,
                                "check_in" => Carbon::parse($brandVisitOverlapped->check_in)->format('Y-m-d'),
                                "check_out" => Carbon::parse($brandVisitOverlapped->check_out)->format('Y-m-d'),
                                "channel" => null,
                                "agency" => null,
                                "telephone" => null,
                                "is_client" => $brandVisitOverlapped->is_client,
                                "satisfaction" => [
                                    "answer" => null,
                                    "comment" => null,
                                    "created_at" => null
                                ],
                                "connections" => []
                            ],
                            [
                                "id" => $brandVisitNotOverlapped->id,
                                "user_brand_id" => $userBrandChain->id,
                                "pms_validated" => false,
                                "pms_id" => null,
                                "reservation_id" => null,
                                "check_in" => Carbon::parse($brandVisitNotOverlapped->check_in)->format('Y-m-d'),
                                "check_out" => Carbon::parse($brandVisitNotOverlapped->check_out)->format('Y-m-d'),
                                "channel" => null,
                                "agency" => null,
                                "telephone" => null,
                                "is_client" => $brandVisitNotOverlapped->is_client,
                                "satisfaction" => [
                                    "answer" => null,
                                    "comment" => null,
                                    "created_at" => null
                                ],
                                "connections" => []
                            ]
                        ]
                    ]
                ]
            ]);
    }

    private function createSurveyAnswer($hotelId, $userId, $brandId, $date)
    {
        $userSatisfaction = factory(Satisfaction::class)->create([
            'id_hotel' => $hotelId,
            'id_usuario' => $userId,
            'done' => 0,
        ]);

        $survey = factory(Survey::class)->create([
            'brand_id' => $brandId,
            'name' => 'survey_name'
        ]);
        $genericQuestion = QuestionBrand::whereNull('brand_id')->first();

        $userSurvey = factory(UserSurvey::class)->create([
            'survey_id'             => $survey->id,
            'brand_id'              => $brandId,
            'user_id'               => $userId,
            'user_satisfaction_id'  => $userSatisfaction->id,
            'review_sent'           => 0,
            'created_at'            => $date
        ]);

        $surveyQuestion = factory(SurveyQuestion::class)->create([
            'survey_id'     => $survey->id,
            'question_id'   => $genericQuestion->question_id,
            'active'        => 1,
            'required'      => 1
        ]);
        
        return factory(UserSurveyQuestionAnswer::class)->create([
            'user_survey_id'        => $userSurvey->id,
            'survey_question_id'    => $surveyQuestion->id
        ]);
    }

    private function createChain($hotels)
    {
        $chain = factory(Cadena::class)->create();

        foreach ($hotels as $hotel) {
            factory(CadenaHotel::class)->create(
                [
                    'id_cadena' => $chain->id,
                    'id_hotel'  => $hotel->id
                ]
            );
        }
    }

    private function createUserBrand($brandId, $user)
    {
        return factory(UserBrand::class)->create([
            'brand_id'      => $brandId,
            'user_id'       => $user->id,
            'date'          => Carbon::yesterday()->setTimezone('UTC'),
            'unsubscribed'  => 1,
            'user_data'     => json_encode([
                "name"          => $user->name,
                "first_name"    => $user->first_name,
                "last_name"     => $user->last_name,
                "email"         => $user->email,
                "gender"        => $user->gender,
                "birthdate"     => $user->birthday,
                "phone_number"  => data_get($user->data, 'phone_number'),
                "generation"    => $user->generation,
                "lang"          =>  $user->lang,
                "country"       => $user->country,
            ])
        ]);
    }

    private function createVisit($userBrand, $checkIn, $checkOut)
    {
        return factory(Visit::class)->create([
            'user_brand_id' => $userBrand->id,
            'brand_id'      => $userBrand->brand_id,
            'user_id'       => $userBrand->user_id,
            'check_in'      => $checkIn,
            'check_out'     => $checkOut
        ]);
    }
}
