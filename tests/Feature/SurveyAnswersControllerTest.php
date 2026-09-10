<?php

namespace Tests\Feature;

use App\Brand;
use App\Cadena;
use App\Hotel;
use App\HotelSatisfaction;
use App\CadenaHotel;
use App\User;
use App\Satisfaction;
use App\SatisfactionAnswer;
use App\Category;
use App\Question;
use App\QuestionBrand;
use App\QuestionText;
use App\Survey;
use App\SurveyQuestion;
use App\UserSurvey;
use App\UserSurveyQuestionAnswer;
use App\Http\Middleware\AmazonRequest;
use App\Jobs\ProcessSaveSurveyAnswer;
use App\Services\Visits\VisitService;
use App\Repositories\Visits\UserBrandRepository;
use Mockery;
use Tests\CreatesApplication;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Bus;

class SurveyAnswersControllerTest extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    protected $expectedResponseMapping;

    public function setUp(): void
    {
        parent::setUp();

        $this->expectedResponseMapping = [
            'external' => [
                'keys'  => [
                    "id",
                    "name",
                    "country",
                    "score",
                    "comment",
                    "lang",
                    "category",
                    "avatar",
                    "brand",
                    "roomID",
                    "created_at",
                    "visit"
                ],
                'error' => [
                    400 => "The brands on list do not belong to same chain.",
                    500 => config('external-api.default-error-message')
                ]
            ],
            'internal' => [
                'keys'  => [
                    "id",
                    "survey_question_id",
                    "user_survey_id",
                    "user_id",
                    "score",
                    "name",
                    "comment",
                    "lang",
                    "favorite",
                    "created_at",
                    "updated_at",
                ],
                'error' => [
                    400 => "The brands on list do not belong to same chain.",
                    500 => 'No query results for model [App\Brand] X'
                ]
            ],
        ];
    }

    public function testCreateSurveysWithAnswersForHotel()
    {
        // Config
        $nAnswers = 4;
        $pEspUsers = 0.5;
        $nSurveys = 2;
        // Generate more than one to have surveys for more than one brand on DB
        $surveys = collect(range(1, $nSurveys))
            ->map(function ($count) use ($nAnswers, $pEspUsers) {
                return $this->prepareSurveyWithAnswers($pEspUsers, $nAnswers);
            });

        // Select randomly from surveys list
        $survey = $surveys->random();

        // List userSurveysQuestionAnswers for this survey
        $userSurveysQuestionAnswersIds = $survey
            ->userSurveys
            ->map(function ($userSurvey) {
                return $userSurvey
                    ->userSurveyQuestionAnswers
                    ->pluck('id');
            })
            ->collapse();

        // Calc n users by lang
        [$nAnswersEsp, $nAnswersEng] = $this->calcNumberUsersByLang($pEspUsers, $nAnswers);

        // ---- Assert that the endpoint get all survey answers
        // Perform request to get surveys
        $response = $this->json(
            'GET',
            "/api/brands/{$survey->brand_id}/surveys/satisfactions/answers"
        )
            ->assertStatus(200);

        // Get response content
        $content = $response->json();
        $responseIds = collect($content['data'])->pluck('id');

        // Check if the ids on the DB has all ids that become on response
        $this->assertEquals($responseIds->count(), $nAnswers);
        $this->assertEquals($content['meta']['current_page'], 1);
        $this->assertEquals($content['meta']['per_page'], config('services.surveys.paginate.default'));
        $this->assertTrue($responseIds->diff($userSurveysQuestionAnswersIds)->isEmpty());

        // ---- Assert Paginate works that the endpoint get all survey answers
        $page = 1;
        $itemsPerPage = round($nAnswers / 2);
        // Perform request to get surveys
        $response = $this->json(
            'GET',
            "/api/brands/{$survey->brand_id}/surveys/satisfactions/answers?page={$page}&page_items={$itemsPerPage}"
        )
            ->assertStatus(200);

        // Get response content
        $content = $response->json();
        $responseIds = collect($content['data'])->pluck('id');

        // Check if the ids on the DB has all ids that become on response
        $this->assertEquals($responseIds->count(), $itemsPerPage);
        $this->assertEquals($content['meta']['current_page'], $page);
        $this->assertEquals($content['meta']['per_page'], $itemsPerPage);
        $this->assertTrue($responseIds->diff($userSurveysQuestionAnswersIds)->isEmpty());

        // ---- Assert Paginate works that the endpoint get all survey answers for lang ENG
        $page = 1;
        $itemsPerPage = $nAnswers;
        // Perform request to get surveys
        $response = $this->json(
            'GET',
            "/api/brands/{$survey->brand_id}/surveys/satisfactions/answers?page={$page}&page_items={$itemsPerPage}"
        )
            ->assertStatus(200);

        // Get response content
        $content = $response->json();
        $responseIds = collect($content['data'])->pluck('id');

        // Check if the ids on the DB has all ids that become on response
        $this->assertEquals($responseIds->count(), $nAnswers);
        $this->assertEquals($content['meta']['current_page'], $page);
        $this->assertEquals($content['meta']['per_page'], $nAnswers);
        $this->assertTrue($responseIds->diff($userSurveysQuestionAnswersIds)->isEmpty());

        // ---- Assert survey answers for lang ESP
        // Perform request to get surveys
        $response = $this->json(
            'GET',
            "/api/brands/{$survey->brand_id}/surveys/satisfactions/answers?language=es"
        )
            ->assertStatus(200);

        // Get response content
        $content = $response->json();
        $responseIds = collect($content['data'])->pluck('id');

        // Check response is empty for lang ESP
        $this->assertEquals($responseIds->count(), $nAnswersEsp);
        $this->assertEquals($content['meta']['current_page'], $page);
        $this->assertEquals($content['meta']['per_page'], config('services.surveys.paginate.default'));
        $this->assertTrue($responseIds->diff($userSurveysQuestionAnswersIds)->isEmpty());

        // ----  Assert that the endpoint get dont return survey answers for category != N/A
        // Perform request to get surveys
        $response = $this->json(
            'GET',
            "/api/brands/{$survey->brand_id}/surveys/satisfactions/answers?category=test"
        )
            ->assertStatus(200);

        // Get response content
        $content = $response->json();
        $responseIds = collect($content['data'])->pluck('id');

        // Check response is empty for lang ESP
        $this->assertTrue($responseIds->isEmpty());
    }

    public function testCreateSurveysWithAnswersForChain()
    {
        // create a survey for a hotel that belong to a chain
        $survey = $this->prepareSurveyWithAnswers($pEspUsers = 0.5, $nAnswers = 4, true);

        // List userSurveysQuestionAnswers
        $userSurveysQuestionAnswersIds = $survey
            ->userSurveys
            ->map(function ($userSurvey) {
                return $userSurvey
                    ->userSurveyQuestionAnswers
                    ->pluck('id');
            })
            ->collapse();

        // Get brand_id from chain
        $chainBrandId = $survey->brand->parent_id;
        $hotelBrandId = $survey->brand->id;

        // Calc n users by lang
        [$nAnswersEsp, $nAnswersEng] = $this->calcNumberUsersByLang($pEspUsers, $nAnswers);

        // ---- Assert that the endpoint get all survey answers for lang ENG
        // Perform request to get surveys
        $response = $this->json(
            'GET',
            "/api/brands/{$chainBrandId}/surveys/satisfactions/answers"
        )
            ->assertStatus(200);

        // Get response content
        $content = $response->json();
        $responseIds = collect($content['data'])->pluck('id');

        // Check if the ids on the DB has all ids that become on response
        $this->assertEquals($responseIds->count(), $nAnswers);
        $this->assertEquals($content['meta']['current_page'], 1);
        $this->assertEquals($content['meta']['per_page'], config('services.surveys.paginate.default'));
        $this->assertTrue($responseIds->diff($userSurveysQuestionAnswersIds)->isEmpty());

        // ---- Assert survey answers for lang ESP
        // Perform request to get surveys
        $response = $this->json(
            'GET',
            "/api/brands/{$chainBrandId}/surveys/satisfactions/answers?language=es"
        )
            ->assertStatus(200);

        // Get response content
        $content = $response->json();
        $responseIds = collect($content['data'])->pluck('id');

        // Check response is empty for lang ESP
        $this->assertEquals($responseIds->count(), $nAnswersEsp);
        $this->assertEquals($content['meta']['current_page'], 1);
        $this->assertEquals($content['meta']['per_page'], config('services.surveys.paginate.default'));
        $this->assertTrue($responseIds->diff($userSurveysQuestionAnswersIds)->isEmpty());

        // ---- Assert 400 when passing a brands with a brand_id from another hotel
        $wrongBrandId = Brand::pluck('id')->last() + 1;
        // Perform request to get surveys
        $response = $this->json(
            'GET',
            "/api/brands/{$chainBrandId}/surveys/satisfactions/answers?language=es&brands={$hotelBrandId},{$wrongBrandId}"
        )
            # TODO: Change this statusCode to 500
            ->assertStatus(400);
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

        // Mock VisitService and UserBrandRepository
        $visitService = Mockery::mock(VisitService::class);
        $userBrandRepository = Mockery::mock(UserBrandRepository::class);

        $visitService->shouldReceive('getVisit')
        ->andReturn((object) [
       
        'reservation' => json_encode([
            'check_in' => '2023-09-01 12:00:00',
            'check_out' => '2023-09-05 12:00:00',
            'res_channel' => 'Online'
        ]),
        ]);

        $userBrandRepository->shouldReceive('get')
        ->andReturn((object) ['id' => 123]);

        \App::instance(VisitService::class, $visitService);
        \App::instance(UserBrandRepository::class, $userBrandRepository);

        // Get a survey
        $survey = $this->prepareSurveyWithAnswers($pEspUsers = 0.5, $nAnswers = 4, true);
        // Get brand_id from chain
        $chainBrandId = $survey->brand->parent_id;
        $hotelBrandId = $survey->brand->id;
        $wrongBrandId = Brand::pluck('id')->last() + 1;

        // ------ TEST EXTERNAL CALL
        // Perform request to get surveys
        $response = $this->json(
            'GET',
            "/api/brands/{$chainBrandId}/surveys/satisfactions/answers?language=es",
            ['Accept' => 'application/json'],
            ['x-amzn-apigateway-api-id' => 'external']
        )
            ->assertStatus(200);

        // Get response content
        $content = $response->json();
        $resposeKeys = array_keys(Arr::first($content['data']));

        $this->assertEqualsCanonicalizing($this->expectedResponseMapping['external']['keys'], $resposeKeys);

        // ------ TEST INTERNAL CALL
        // Perform request to get surveys
        $response = $this->json(
            'GET',
            "/api/brands/{$chainBrandId}/surveys/satisfactions/answers?language=es",
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
        $response = $this->json(
            'GET',
            "/api/brands/{$chainBrandId}/surveys/satisfactions/answers?language=es&brands={$hotelBrandId},{$wrongBrandId}",
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
        $response = $this->json(
            'GET',
            "/api/brands/{$chainBrandId}/surveys/satisfactions/answers?language=es&brands={$hotelBrandId},{$wrongBrandId}",
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
        $response = $this->json(
            'GET',
            "/api/brands/{$wrongBrandId}/surveys/satisfactions/answers",
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
        $response = $this->json(
            'GET',
            "/api/brands/{$wrongBrandId}/surveys/satisfactions/answers",
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

    public function testFilterValidationRules()
    {
        // Get a survey
        $survey = $this->prepareSurveyWithAnswers($pEspUsers = 0.5, $nAnswers = 4, true);
        // Get brand_id from chain
        $chainBrandId = $survey->brand->parent_id;
        $hotelBrandId = $survey->brand->id;
        $wrongBrandId = Brand::pluck('id')->last() + 1;

        // Set function to assert all validations
        $assertRequestFn = function ($brandId, $responseCode) {
            return function ($val, $key) use ($brandId, $responseCode) {
                $response = $this->json(
                    'GET',
                    "/api/brands/{$brandId}/surveys/satisfactions/answers?{$key}={$val}",
                    ['Accept' => 'application/json'],
                    []
                )
                    ->assertStatus($responseCode);
            };
        };

        // Assert 400 responses validations
        collect([
            'page_items' => 'x',
            'page_items' => 501,
            'page_items' => 0,
            'page'       => 'x',
            'brands'     => 0,
            'brands'     => 'x',
            'language'   => 1,
            'language'   => 'pt_br',
            'language'   => 'xxx',
            'language'   => 'x',
            'score'      => 'x',
            'score'      => 11,
            'score'      => - 1,
            'favorite'   => 'x',
            'favorite'   => - 1,
            'favorite'   => 2,
            'date_from'  => 0,
            'date_from'  => '2019',
            'date_from'  => '01-02-2019',
            'date_from'  => '2019/01/01',
            'date_from'  => '2019-99-99',
            'date_from'  => '2019-01-01 00:00:00',
            'date_to'    => 0,
            'date_to'    => '2019',
            'date_to'    => '01-02-2019',
            'date_to'    => '2019/01/01',
            'date_to'    => '2019-99-99',
            'date_to'    => '2019-01-01 00:00:00',
        ])
            ->each($assertRequestFn($chainBrandId, 400));

        // Assert 200 responses validations
        collect([
            'page_items' => 1,
            'page_items' => 500,
            'page'       => 1,
            'brands'     => $hotelBrandId,
            'language'   => 'es',
            'language'   => 'en',
            'score'      => 0,
            'score'      => 10,
            'score'      => 5,
            'favorite'   => 0,
            'favorite'   => 1,
            'category'   => 'ANY',
            'date_from'  => '2019-01-01',
            'date_to'    => '2019-01-01',
        ])
            ->each($assertRequestFn($chainBrandId, 200));
    }

    public function testSaveAnswerWithBadDataFails()
    {
        Bus::fake();
        $this->json('POST', "/api/brands/1/surveys/1/answers", [])->assertStatus(400);
        Bus::assertNotDispatched(ProcessSaveSurveyAnswer::class);
    }

    public function testJobDispatchedIfSaveAnswerSucessfully()
    {
        Bus::fake();
        $this->json('POST', "/api/brands/1/surveys/1/answers", ["user_id" => 1])->assertStatus(202);
        Bus::assertDispatched(ProcessSaveSurveyAnswer::class);
    }

    /**
     * Calculate the number of anwsers by a number total and portion of lang esp
     *
     * @param float $pLangEs
     * @param int $nUsers
     *
     * @return array
     */
    private function calcNumberUsersByLang(float $pLangEs, int $nUsers): array
    {
        // Calc n users by lang
        $nAnswersEsp = (int)round($pLangEs * $nUsers);
        $nAnswersEng = (int)$nUsers - $nAnswersEsp;

        return [$nAnswersEsp, $nAnswersEng];
    }

    /**
     * Create a survey with a number of answers for a hotel/chainHotel
     *
     * @param float $pLangEs Percentage on ESP users
     * @param int $nAnswers Number of Users/Answers
     * @param bool $chainHotels Define if the hotel created belongs to a chain
     *
     * @return Survey
     */
    private function prepareSurveyWithAnswers($pLangEs = 0.5, $nAnswers = 8, $chainHotels = false)
    {
        if ($chainHotels) {
            $hotel = factory(Hotel::class)->create();
            $chain = factory(Cadena::class)->create();
            factory(CadenaHotel::class)->create(
                [
                    'id_cadena' => $chain->id,
                    'id_hotel'  => $hotel->id
                ]
            );
        } else {
            $hotel = factory(Hotel::class)->create();
        }

        // $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $brand = $hotel->brand;

        // Create a survey for this brand
        $survey = factory(Survey::class)
            ->create([
                'brand_id' => $brand->id,
            ]);

        // Get the category for satisfaction survey
        $category = Category::where('brand_id', null)->first();

        // Get the question
        $question = $category->question()->first();

        // Create a surveyQuestion
        $surveyQuestion = factory(SurveyQuestion::class)
            ->create([
                'survey_id'   => $survey->id,
                'question_id' => $question->id,
            ]);

        // Get n of users by lang
        [$nEspUsers, $nEngUsers] = $this->calcNumberUsersByLang($pLangEs, $nAnswers);

        // Create ESP Users
        $usersEsp = factory(User::class, $nEspUsers)
            ->create([
                'lang' => 'es'
            ]);
        // Create ENG Users
        $usersEng = factory(User::class, $nEngUsers)
            ->create([
                'lang' => 'en'
            ]);
        // Concat both users
        $users = $usersEsp->concat($usersEng);

        // Loop each user to create a answer
        $users
            ->each(function ($user) use ($survey, $surveyQuestion, $brand) {
                // Create a Satisfaction Answer because the new surveys has a FK for this table
                $userSatisfaction = factory(Satisfaction::class)
                    ->create([
                        'id_usuario'    => $user->id,
                        'id_hotel'      => $brand->hotel_id,
                        'id_cadena'     => $brand->parent->chain_id ?? 0,
                        'done'          => 1
                    ]);

                // Create UserSurvey
                $userSurvey = factory(UserSurvey::class)
                    ->create([
                        'survey_id'            => $survey->id,
                        'brand_id'             => $brand->id,
                        'user_id'              => $user->id,
                        'user_satisfaction_id' => $userSatisfaction->id,
                    ]);

                // Create UserSurveyQuestionAnswer
                factory(UserSurveyQuestionAnswer::class)
                    ->create([
                        'user_survey_id'     => $userSurvey->id,
                        'survey_question_id' => $surveyQuestion->id,
                    ]);
            });

        return $survey;
    }
}
