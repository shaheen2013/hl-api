<?php

namespace Tests\Feature;

use App\User;
use App\Brand;
use App\Category;
use App\Hotel;
use App\Satisfaction;
use App\Question;
use App\Survey;
use App\SurveyQuestion;
use App\UserSurvey;
use App\UserSurveyQuestionAnswer;
use Carbon\Carbon;
use App\Jobs\ProcessSurveyExport;
use App\Types\Survey\SurveyExportDataType;
use App\Services\Clients\ClientService;
use App\Services\BrandService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class WorkerSurveyExportTest extends TestCase
{
    public function testCreateSurveyCsvReportSuccessfully()
    {
        Storage::fake('s3');

        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $user = factory(User::class)->create();

        $survey = factory(Survey::class)->create([
            'brand_id' => $brand->id,
            'name' => 'survey_name'
        ]);

        $category = factory(Category::class)->create(['brand_id' => $brand->id]);

        $question = factory(Question::class)->create(['category_id' => $category->id]);

        $userSatisfaction = factory(Satisfaction::class)->create([
            'id_hotel'      => $hotel->id,
            'id_usuario'    => $user->id,
            'puntuacion'    => 10,
            'done'          => 1
        ]);

        $userSurvey = factory(UserSurvey::class)->create([
            'survey_id' => $survey->id,
            'brand_id' => $brand->id,
            'user_id' => $user->id,
            'user_satisfaction_id' => $userSatisfaction->id
        ]);

        $surveyQuestion = factory(SurveyQuestion::class)->create([
            'survey_id' => $survey->id,
            'question_id' => $question->id,
            'active' => 1,
            'required' => 1
        ]);

        $satisfaction = factory(UserSurveyQuestionAnswer::class)->create([
            'user_survey_id' => $userSurvey->id,
            'survey_question_id' => $surveyQuestion->id,
            'answer' => 10,
        ]);

        $request = new \Illuminate\Http\Request();
        $request->replace([
            'emails' => ['test@testing.com']
        ]);
        $data = new SurveyExportDataType($request);


        $job = new ProcessSurveyExport($brand->id, $data);
        $job->handle(app('App\Services\SatisfactionService'), app('App\Services\BrandService'));

        $this->assertNotEquals([], Storage::disk('s3')->allFiles());
    }
}
