<?php

namespace App\Console\Commands;


use App\Brand;
use App\SatisfactionAnswer;
use App\UserSurvey;
use App\UserSurveyQuestionAnswer;
use App\SurveyQuestion;

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RecoverCustomizedSurveyLost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotelinking:recover-customized-survey {brand_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recover the customized survey lost in the new table system';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $brandId = $this->argument('brand_id');


        if (!$brandId) {
            $this->getOutput()->error("No brandId or accountId defined in the command");
            return 0;
        }

        $brand = Brand::where(['id' => $brandId])->first();

        if (empty($brand)) {
            $this->getOutput()->error("No brand found in database with this ID");
            return 0;
        }

        $output = new ConsoleOutput();
        $progress = new ProgressBar($output, SatisfactionAnswer::where(["brand_id" => $brand->id])->count());
        $progress->start();


        SatisfactionAnswer::where(["brand_id" => $brand->id])->chunk(10000, function ($customizedSurveyAnswers) use ($progress) {
            foreach ($customizedSurveyAnswers as $customizedSurveyAnswer) {
                // The column is called survey_question_id, but the relation is made against the question table, not survey_question
                $questionId = $customizedSurveyAnswer->survey_question_id;
                $userSatisfactionId = $customizedSurveyAnswer->user_satisfaction_id;

                $userSurvey = UserSurvey::where(['user_satisfaction_id' => $userSatisfactionId])->first();
                $surveyQuestion = SurveyQuestion::where(['question_id' => $questionId])->first();
                
                if (data_get($surveyQuestion, 'id') && data_get($userSurvey, 'id')) {
                    UserSurveyQuestionAnswer::firstOrCreate(
                        [
                            "survey_question_id" => data_get($surveyQuestion, 'id'),
                            "user_survey_id" => data_get($userSurvey, 'id'),
                            "answer" => $customizedSurveyAnswer->answer,
                            "comment" => $customizedSurveyAnswer->comment,
                            "question_response_id" => $customizedSurveyAnswer->question_response_id
                        ],
                        [
                            "favorite" => 0,
                            "created_at" => $customizedSurveyAnswer->created_at,
                            "updated_at" => $customizedSurveyAnswer->created_at
                        ]
                    );
                }
                
                $progress->advance();
            }
        });

        Cache::tags("surveys-$brand->id")->flush();

        
        $progress->finish();
        $output->write(PHP_EOL);
    }
}
