<?php

namespace App\Repositories\Satisfactions;

use App\Survey;
use App\Satisfaction;
use App\User;
use App\Types\Survey\GetSurveyDataType;
use App\Traits\Filters;
use App\UserBrand;
use App\Visit;
use Symfony\Component\HttpFoundation\File\Exception\CannotWriteFileException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SurveyRepository
{
    use Filters;

    protected $survey;
    protected $satisfaction;
    protected $visit;
    protected $userBrand;

    public function __construct(Survey $survey, Satisfaction $satisfaction, Visit $visit, UserBrand $userBrand)
    {
        $this->survey = $survey;
        $this->satisfaction = $satisfaction;
        $this->visit = $visit;
        $this->userBrand = $userBrand;
    }

    public function get($brandID)
    {
        return $this->survey->where('brand_id', $brandID)->first();
    }

    public function firstOrCreate($brandID, $name)
    {
        return $this->survey->firstOrCreate(
            ["brand_id" => $brandID],
            ["name" => $name]
        );
    }

    public function getSurveys(GetSurveyDataType $data)
    {
        $satisfactions = $this->satisfaction->select('users.*', 'hoteles.*', 'user_satisfaction.*')->with(['user',
            'hotel',
            'user.userFacebook',
            'satisfactionAnswer',
            'satisfactionAnswer.question',
            'satisfactionAnswer.question.questiontext',
            'satisfactionAnswer.question.category',
            'satisfactionAnswer.question.category.categoryText',
            'userSurvey.incidents.hotelStaff'
            ])->where("$data->brandType", $data->brandTypeId)
            ->join('users', 'user_satisfaction.id_usuario', 'users.id')
            ->join('hoteles', 'user_satisfaction.id_hotel', 'hoteles.id');

        if ($data->satisfactionId) {
            $satisfactions->where('user_satisfaction.id', $data->satisfactionId);
        } else {
            $satisfactions->where('user_satisfaction.done', 1);
        }

        if (isset($data->lang)) {
            $satisfactions = $satisfactions->with('user')
                ->whereHas("user", function ($q) use ($request) {
                    $q->where("lang", $data->lang);
                });
        }

        if (isset($data->score)) {
            $satisfactions = $satisfactions->where('puntuacion', '>=', $data->score);
        }

        if (isset($data->days)) {
            $satisfactions = $satisfactions->whereDate('fecha_creado', '>=', date('Y-m-d H:i:s', strtotime('+' . $data->days . ' days')));
        }

        if (isset($data->search)) {
            if (str_contains($data->search, '@')) {
                $satisfactions = $satisfactions->where('users.email', 'LIKE', '%' . $data->search . '%');
            } else {
                $satisfactions = $satisfactions->where(function ($query) use ($data) {
                    $query->where('user_satisfaction.comentario', 'LIKE', '%' . $data->search . '%')
                        ->orWhere('user_satisfaction.id_room', 'LIKE', '%' . $data->search . '%');
                });
            }
        }

        if (isset($data->from)) {
            $satisfactions = $satisfactions->whereDate('fecha_update', '>=', $data->from);
        }

        if (isset($data->to)) {
            $satisfactions = $satisfactions->whereDate('fecha_update', '<=', $data->to);
        }

        if (isset($data->limit)) {
            $satisfactions = $satisfactions->limit($data->limit);
        }

        if (isset($data->sortOrder) && isset($data->sortField)) {
            $satisfactions = $satisfactions->orderBy($data->sortField, $data->sortOrder);
        } else {
            $satisfactions = $satisfactions->orderBy('user_satisfaction.id', 'desc');
        }

        return $satisfactions;
    }

    public function getCsv($data, $customizedQuestions, $headers)
    {
        $filename = '/export_survey_csv-' . str_replace('.', '-', microtime(true)) . '.tmp';
        $localFile = sys_get_temp_dir() . $filename;
        $csv = fopen($localFile, 'w+');
        fprintf($csv, chr(0xEF) . chr(0xBB) . chr(0xBF));

        if (!$csv) {
            Log::error('Cannot write file', ['file' => $localFile]);
            throw new CannotWriteFileException();
        }

        fputcsv($csv, $headers, ";");

        $surveys = $this->getSurveys($data);

        foreach ($surveys->cursor() as $survey) {
            $timeBetweenResponse = Carbon::parse(data_get($survey, 'fecha_update'))->diff(Carbon::parse(data_get($survey, 'send_date')));

            $incidents = "";
            foreach (data_get($survey, 'userSurvey.incidents', []) as $incident) {
                Log::debug("Normal INCIDENTS", [$incident]);

                $staff = $incident->hotelStaff ?? null;
                $staffName = $staff ? $staff->nombre : "Main Account";
                $disabled = $staff && $staff->deleted ? ", disabled user" : "";
                $incidents .= "- " . $incident->incident_text . " (" . $staffName . $disabled .  ") \n";
            }
            $assistedAt = data_get($survey, 'userSurvey.assisted_at');

            $visits = [];
            $userId = data_get($survey, "user.id");
            $brandId = data_get($survey, "userSurvey.brand_id");
            $createdAt = Carbon::parse(data_get($survey, 'userSurvey.created_at'))->addHour();

            try {
                $userBrand = $this->userBrand->where('user_id', $userId)
                    ->where('brand_id', $brandId)
                    ->first();
                        
                if ($userBrand !== null) {
                    $relatedVisit = $this->visit->where('user_brand_id', $userBrand->id)
                        ->where('check_in', '<=', $createdAt)
                        ->where('check_out', '>=', $createdAt)
                        ->first();
                    
                    if ($relatedVisit) {
                        $reservationData = json_decode($relatedVisit->reservation);
                        $resChannel = $reservationData->res_channel;
        
                        $visits [] = [
                            'checkIn' => $relatedVisit->check_in,
                            'checkOut' => $relatedVisit->check_out,
                            'resChannel' => $resChannel,
                        ];
                    }
                }
            } catch (\Exception $e) {
                Log::debug("Error: " . $e->getMessage());
            }

            $row = [
                data_get($survey, 'hotel.name'),
                data_get($survey, 'user.nombre'),
                data_get($survey, 'id_room', 'N/A'),
                data_get($survey, 'user.country'),
                data_get($survey, 'user.birthday'),
                data_get($survey, 'user.gender'),
                "How has your experience been so far?",
                data_get($survey, 'puntuacion'),
                data_get($survey, 'comment'),
                "N/A",
                Carbon::parse(data_get($survey, 'send_date'))->gte('2000-01-01') ? $timeBetweenResponse->days . ' days and ' . $timeBetweenResponse->h . ' hours' : "N/A" ,
                data_get($survey, 'send_date'),
                data_get($survey, 'fecha_update'),
                $incidents,
                $assistedAt,
                $visits ? $visits[0]['checkIn'] : "",
                $visits ? $visits[0]['checkOut'] : "",
                $visits ? $visits[0]['resChannel'] : "",
            ];

            fputcsv($csv, $row, ";");

            if (data_get($survey, 'satisfactionAnswer')->isNotEmpty()) {
                foreach ($customizedQuestions->toArray(new Request($data->request)) as $customizedQuestion) {
                    // Create row for each customized question
                    $category = Arr::get($customizedQuestion, 'category_text.en');

                    foreach (data_get($customizedQuestion, 'questions') as $question) {
                        $customizedAnswers = data_get($survey, 'satisfactionAnswer')->filter(function ($answer) use ($question) {
                            return data_get($question, 'id') == data_get($answer, 'survey_question_id');
                        });

                        $firstCustomizedAnswer = $customizedAnswers->first();

                        $response = isset($firstCustomizedAnswer->answer) ? $firstCustomizedAnswer->answer : "NR/DK";
                        foreach ($customizedAnswers as $answer) {
                            if ($answer->question_response_id) {
                                $previousResponses = $response !== "NR/DK" ? $response . ", " : "";

                                $response =  $previousResponses . Arr::first(data_get($answer, 'questionResponse.questionResponseText'), function ($questionResponse) use ($question) {
                                    return data_get($questionResponse, 'lang_value') == 'en';
                                })->text;
                            }
                        }

                        $score = $firstCustomizedAnswer ?
                            $response :
                            'N/R';

                        $comment = $firstCustomizedAnswer && $firstCustomizedAnswer->comment ?
                            $firstCustomizedAnswer->comment :
                            ($score === 'NR/DK' ?
                                'NR/DK' :
                                'N/R');

                        $score = data_get($question, 'type') == "Open Question"
                            ? ""
                            : $score;

                        $timeBetweenResponse = Carbon::parse(data_get($survey, 'customSendDate'))->diff(Carbon::parse(data_get($survey, 'sendDate')));
                        $timeBetweenCustomizedResponse = $score !== "NR/DK" && $score !== "N/R" ?
                            $timeBetweenResponse->days . ' days and ' . $timeBetweenResponse->h . ' hours' :
                            "N/A";

                        $row =  [
                            data_get($survey, 'hotel.name'),
                            data_get($survey, 'user.nombre'),
                            data_get($survey, 'id_room', 'N/A'),
                            data_get($survey, 'user.country'),
                            data_get($survey, 'user.birthday'),
                            data_get($survey, 'user.gender'),
                            Arr::get($question, 'question_text.en'),
                            $score,
                            $comment,
                            $category,
                            $timeBetweenCustomizedResponse,
                            data_get($firstCustomizedAnswer, 'created_at', 'N/A'),
                            ""
                        ];

                        fputcsv($csv, $row, ";");
                    };
                }
            }
        }

        fclose($csv);

        return $localFile;
    }
}
