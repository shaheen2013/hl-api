<?php

use Illuminate\Database\Seeder;
use App\Survey;
use App\UserSurvey;
use App\QuestionBrand;
use App\UserSurveyQuestionAnswer;
use App\SurveyQuestion;

class CreateFakeHotelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Hotel::class, (int)env('INDIPENDENT_HOTEL_FACTORY_THRESHOLD'))->create()->each(
            function ($hotel) {
                $hotel->hotelGuid()->save(factory(App\HotelGuid::class)->make());
                $hotel->langHotel()->save(factory(App\LangHotel::class)->make());
                // Get brand
                $brand = $hotel->brand;

                //add staisfaction config
                DB::table('hotel_satisfaction')->insert(
                    array(
                        'id_hotel'             => $hotel->id,
                        'diasEnvio'            => 15,
                        'puntMin'              => 5,
                        'review_average_score' => 5,
                        'ignoreRating'         => 0,
                        'sendThanksMail'       => 1,
                        'sendToNonCustomers'   => 0,
                        'send_hour'            => 0,
                        'total_followup_email' => 0
                    )
                );

                //add review config
                DB::table('hotel_review')->insert(
                    array(
                        'id_hotel'     => $hotel->id,
                        'diasEnvio'    => 15,
                        'ignoreRating' => 0
                    )
                );

                // Start showing progress bar
                dump("Inserting users for hotel: " . $hotel->id . " - " . $hotel->hotelName);
                $this->command->getOutput()->progressStart(env('INDIPENDENT_HOTEL_USER_FACTORY_THRESHOLD'));
                
                $survey = factory(Survey::class)->create(['brand_id' => $brand->id]);
                $genericQuestion = QuestionBrand::whereNull('brand_id')->first();
                $genericSurveyQuestion = factory(SurveyQuestion::class)->create([
                    'survey_id'     => $survey->id,
                    'question_id'   => $genericQuestion->question_id,
                    'active'        => 1,
                    'required'      => 1
                ]);

                for ($newFakeUser = 0; $newFakeUser < env('INDIPENDENT_HOTEL_USER_FACTORY_THRESHOLD'); $newFakeUser++) {
                    //create User
                    factory(App\User::class, 1)->create()->each(
                        function ($user) use ($hotel, $brand, $survey, $genericSurveyQuestion) {
                            $user->userGuid()->save(factory(App\UserGuid::class)->make());
                            //create user_hotels
                            $user->userHotels()->save(factory(App\UserHotel::class)->make(['id_hotel' => $hotel->id, 'id_usuario' => $user->id]));
                            //create user_visits
                            $user->visits()->save(factory(App\UserVisits::class)->make(['hotel_id' => $hotel->id, 'user_id' => $user->id]));
                            //create connection_history
                            $hotel->connectionHistory()->save(factory(App\ConnectionHistory::class)->make(['id_hotel' => $hotel->id, 'id_user' => $user->id]));
                            //Create Satisfaction
                            $userSatisfaction = $hotel->userSatisfaction()->save(factory(App\Satisfaction::class)->make([
                                'id_hotel' => $hotel->id, 
                                'id_usuario' => $user->id, 
                                'id_cadena' => 0,
                                'has_been_seen' => rand(0, 1)
                            ]));
                            
                            $assistedAt = null;
                            if ($userSatisfaction->has_been_seen == 1) {
                                $assistedAt = now()->subDays(rand(1, 30)); 
                            } 

                            // Crear UserSurvey directamente para cada UserSatisfaction
                            $userSurvey = factory(UserSurvey::class)->create([
                                'survey_id'             => $survey->id,
                                'brand_id'              => $brand->id,
                                'user_id'               => $userSatisfaction->id_usuario, 
                                'user_satisfaction_id'  => $userSatisfaction->id,
                                'assisted'              => $userSatisfaction->has_been_seen,
                                'assisted_at'           => $assistedAt
                            ]);

                            // Si la satisfacción del usuario está marcada como completada, crear respuestas a preguntas genéricas
                            if ($userSatisfaction->done) {
                                factory(UserSurveyQuestionAnswer::class)->create([
                                    'survey_question_id'    => $genericSurveyQuestion->id,
                                    'user_survey_id'        => $userSurvey->id,
                                    'answer'                => $userSatisfaction->puntuacion,
                                    'comment'               => $userSatisfaction->comentario
                                ]);
                            }
                            
                            // New tables
                            $userBrand = factory(App\UserBrand::class)->create([
                                'brand_id'  => $brand->id,
                                'user_id'   => $user->id,
                                'date'      => now()->setTimezone('UTC'),
                                'unsubscribed'  => 0
                            ]);
                            factory(App\Visit::class)->create([
                                'user_brand_id' => $userBrand->id,
                                 'brand_id'  => $brand->id,
                                 'user_id'   => $user->id,
                            ]);
                        }
                    )->make();

                    // Showing progress status
                    $this->command->getOutput()->progressAdvance();

                }

                // Showing finished progress bar status
                $this->command->getOutput()->progressFinish();

                //create Offer
                factory(App\Offer::class, rand(1, 3))->create(['id_hotel' => $hotel->id, 'id_cadena' => 0])->each(
                    function ($offer) use ($hotel) {
                        //create offer lang
                        $offer->offerLang()->save(factory(App\OfferLang::class)->make(['id_oferta' => $offer->id, 'lang' => $hotel->lang]));
                    }
                )->make();

                //Create Staff
                factory(App\HotelStaff::class, rand(1, 3))->create()->each(
                    function ($staff) use ($hotel) {
                        factory(App\HotelStaffHotels::class)->create(['hotel_id' => $hotel->id, 'hotel_staff_id' => $staff->id]);
                    }
                )->make();

                //add wifi integration config
                DB::table('hotel_wifi_integrations')->insert(
                    array(
                        'hotel_id' => $hotel->id,
                        'wifi_id'  => 5
                    )
                );
            }
        );
    }
}