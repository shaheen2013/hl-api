<?php

namespace App\Repositories\Satisfactions;

use App\UserSurveyQuestionAnswer;
use App\Types\Survey\SurveyAnswersDataType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use DB;

class UserSurveyQuestionAnswerRepository
{
    protected $userSurveyQuestionAnswer;
    protected $cacheLifeInSeconds;
    protected $maxPaginate;
    protected $defaultPaginate;

    public function __construct(UserSurveyQuestionAnswer $userSurveyQuestionAnswer)
    {
        $this->userSurveyQuestionAnswer = $userSurveyQuestionAnswer;
        // Configs
        $this->cacheLifeInSeconds = config('services.surveys.cacheLifeInSeconds');
        $this->maxPaginate = config('services.surveys.paginate.max');
        $this->defaultPaginate = config('services.surveys.paginate.default');
    }

    public function save($userSurveyID, $surveyQuestionID, $score, $comment, $responseId)
    {
        $this->userSurveyQuestionAnswer->firstOrcreate(
            [
                "survey_question_id" => $surveyQuestionID,
                "user_survey_id" => $userSurveyID,
                "question_response_id" => $responseId
            ],
            [
                "answer" => $score,
                "comment" => $comment,
            ]
        );
    }

    /**
     *  Method to return the query for satisfaction answers using joins to improve the performance
     *  The query mounted is similar for this above:
     *   SELECT DISTINCT
     *      'users.nombre as name',
     *          'u.email as email',
     *          'u.lang as lang',
     *          'uf.facebook_img as facebookImg',
     *          's.id as surveyId',
     *          'cattext.text as categoryText',
     *          'usqa.*',
     *   FROM user_survey_question_answer usqa
     *   INNER JOIN user_survey us on us.id = usqa.user_survey_id and us.brand_id in $brandIds
     *   INNER JOIN survey s on s.id = us.survey_id
     *   INNER JOIN survey_question sq on sq.survey_id = s.id
     *   INNER JOIN question q on q.id = sq.question_id
     *   INNER JOIN category cat on cat.id = q.category_id
     *   INNER JOIN category_text cattext on cattext.category_id = cat.id and cattext.lang_value = $lang
     *   INNER JOIN users u on u.id = us.user_id and u.lang = $lang
     *   LEFT JOIN user_facebook uf on uf.id_usuario = u.id
     *   WHERE
     *      usqa.created_at >= $dateFrom AND
     *      usqa.created_at <= $dateTo AND
     *      usqa.favorite = $favorite AND
     *      usqa.answer >= $score
     *   ORDER BY usqa.created_at DESC
     *
     * @param array $brandIds
     * @param string $category
     * @param string $lang
     * @param string $dateFrom
     * @param string $dateTo
     * @param bool $favorite
     * @param float $score
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    private function getAnswersQueryByBrandListAndFilters(array $brandIds, ?string $category, ?string $lang, ?string $dateFrom = null, ?string $dateTo = null, ?bool $favorite = null, ?float $score = null): Builder
    {
        $query = $this->userSurveyQuestionAnswer
            ->select([
                'brands.id as brand_id',
                'hoteles.hotelName as hotel_name',
                'users.id as user_id',
                'users.nombre as name',
                'users.email as email',
                'users.lang as lang',
                'users.pais as country',
                'user_facebook.facebook_img as facebookImg',
                'survey.id as surveyId',
                'category_text.text as categoryText',
                'user_survey_question_answer.id as id',
                'user_survey_question_answer.user_survey_id',
                'user_survey_question_answer.favorite',
                'user_survey_question_answer.survey_question_id',
                'user_survey_question_answer.answer',
                'user_survey_question_answer.comment',
                'user_survey_question_answer.created_at as answered_at',
                'user_survey.created_at as created_at',
                'user_survey.updated_at as updated_at',
                'user_satisfaction.id_room as roomID',
            ])
            // ->distinct()
            ->join('user_survey', function ($join) use ($brandIds) {
                $join
                    ->on('user_survey.id', '=', 'user_survey_question_answer.user_survey_id')
                    ->whereIn('user_survey.brand_id', $brandIds);
            })
            ->join('users', function ($join) use ($lang) {
                $join->on('users.id', '=', 'user_survey.user_id');
                // If passed a lang
                if ($lang) {
                    $join->where('users.lang', '=', $lang);
                }
            })
            ->join('user_satisfaction', 'user_satisfaction.id', '=', 'user_survey.user_satisfaction_id')
            ->join('survey', 'survey.id', '=', 'user_survey.survey_id')
            ->join('survey_question', 'survey_question.survey_id', '=', 'survey.id')
            ->join('question', 'question.id', '=', 'survey_question.question_id')
            ->join('category', 'category.id', '=', 'question.category_id')
            ->join('category_text', function ($join) use ($lang, $category) {
                $join
                    ->on('category_text.category_id', '=', 'category.id')
                    // TODO: Remove COLLATE after normalize all DB with the encode
                    ->on('category_text.lang_value', '=', DB::raw('`users`.`lang` COLLATE utf8_unicode_ci'));
                // If passed a category
                if ($category) {
                    $join->where('category_text.text', '=', $category);
                }
            })
            ->leftJoin('user_facebook', 'user_facebook.id_usuario', '=', 'users.id')
            ->join('brands', 'brands.id', '=', 'user_survey.brand_id')
            ->join('hoteles', 'hoteles.id', '=', 'brands.hotel_id');

        // Filter dateFrom
        if ($dateFrom) {
            $query->where('user_survey_question_answer.created_at', '>=', $dateFrom);
        }
        // Filter dateTo
        if ($dateTo) {
            $query->where('user_survey_question_answer.created_at', '<=', $dateTo);
        }
        // Filter favorite
        if (!is_null($favorite)) {
            $query->where('user_survey_question_answer.favorite', $favorite);
        }
        // Filter score
        if (!is_null($score)) {
            $query->where('user_survey_question_answer.answer', '>=', $score);
        }
        // OrderBy to return firts the new ones
        $query->orderBy('user_survey_question_answer.created_at', 'DESC');

        return $query;
    }

    /**
     * Get satisfactions answers applyng some filters passed on a array $filterList
     * Some of this filters are:
     *  - lang: filter comments by user lang
     *  - dateFrom: filter comments since dateFrom
     *  - dateTo: filter comments until dateTo
     *  - favorite: only comments marked as favorite
     *  - score: filter with score >= score filter value
     *  - category: filter only for a category passed, if no one we query for N/A, the default surveys
     *
     *  The paginate filters:
     *  - page: the page wanted
     *  - pageItems: number of items per page
     *
     * @param int $parentBrandId - the brand id from chain or hotel in case of independent hotel.
     * @param int $brandIds - the brand ids to be search comments.
     * @param App\Types\Survey\SurveyAnswersDataType $surveyAnswersDataType
     * @param bool $usingJoins - The boolean to control what query is to performed, joins or eagerLoading.
     *
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginatedAnswerWithFilters(int $parentBrandId, array $brandIds, SurveyAnswersDataType $surveyAnswersDataType): LengthAwarePaginator
    {
        // ---- Get all input filters
        $lang = $surveyAnswersDataType->language;
        $dateFrom = $surveyAnswersDataType->dateFrom;
        $dateTo = $surveyAnswersDataType->dateTo;
        $pageItems = $surveyAnswersDataType->pageItems ?? $this->defaultPaginate;
        $page = $surveyAnswersDataType->page;
        $favorite = $surveyAnswersDataType->favorite;
        $score = !is_null($surveyAnswersDataType->score) ? round($surveyAnswersDataType->score, 2) : null;
        $category = $surveyAnswersDataType->category;

        // Define cache name with filters
        $cacheName = "surveys-parentBrandId:$parentBrandId-brandIds:" . implode(',', $brandIds) . "-category:$category-lang:$lang-date_from:$dateFrom-date_to:$dateTo-favorite:$favorite-score:$score-page:$page-page_items:$pageItems";
        // Define a tag foreach brandId in the query, because when became a new survey answers, we flush the cache that has this tag
        $cacheTag = collect($brandIds)
            ->sort()
            ->map(function ($brandId) {
                return "surveys-$brandId";
            })
            ->toArray();
        // Check cache
        if (Cache::tags($cacheTag)->has($cacheName)) {
            Log::debug("UserSurveyQuestionAnswerRepository", ['message' => 'using cache', "cacheName" => $cacheName, "cacheTag" => $cacheTag]);
            $paginate = Cache::tags($cacheTag)->get($cacheName);
        } else {
            Log::debug("UserSurveyQuestionAnswerRepository", ['message' => 'performing query', "cacheName" => $cacheName, "cacheTag" => $cacheTag]);
            // Get query using joins to improve performance
            $query = $this->getAnswersQueryByBrandListAndFilters($brandIds, $category, $lang, $dateFrom, $dateTo, $favorite, $score);
            // Paginate
            $paginate = $query->paginate($pageItems);
            // attatch filter params to url
            $paginate->appends(array_filter($surveyAnswersDataType->getAttributesForUrl()));
            // Add to cache
            Cache::tags($cacheTag)->put($cacheName, $paginate, $this->cacheLifeInSeconds);
        }

        return $paginate;
    }

    /**
     * Get satisfactions answers with some filters applied (currently used by Hotelinking Widget)
     *  - lang: filter comments by user lang
     *  - favorite: only comments marked as favorite
     *  - score: filter with score >= score filter value
     * @param string $lang - the user lang of the satisfactions requested
     * @param int $brandID - the brand id from chain or hotel in case of independent hotel.
     * @param int $days - the amount of days when the satisfactions were answered
     * @param bool $exclusiveFavorite - filter only the satisfactions marked as favourite
     * @param int $score - minimal satisfaction score to apply to search
     *
     * @return App\UserSurveyQuestionAnswer;
     */
    public function getSatisfactionByLang($lang, $brandID, $days, $exclusiveFavorite, $score, $limit)
    {
        $exclusiveFavorite ? $favoriteSelector = '>' : $favoriteSelector = '>=';

        return $this->userSurveyQuestionAnswer
        ->with('userSurvey.user')
        ->whereHas('userSurvey.user', function ($user) use ($lang) {
            $user->where('lang', $lang);
        })
        ->join('user_survey', 'user_survey_id', '=', 'user_survey.id')
        ->join('brands', 'user_survey.brand_id', '=', 'brands.id')
        ->where([
            ['user_survey.brand_id', '=', $brandID],
            ['comment', '!=', ''],
            ['answer', '>=', $score],
            ['favorite', "$favoriteSelector", 0]
        ])
        ->whereNotNull('comment')
        ->whereDate('user_survey_question_answer.created_at', '>=', date('Y-m-d H:i:s', strtotime('+' . $days . ' days')))
        ->orderBy('favorite', 'desc')
        ->orderBy('user_survey_question_answer.id', 'desc')
        ->take($limit)
        ->select('user_survey_question_answer.*')
        ->get()
        ->load('userSurvey.survey.brand.hotel');
    }

    public function deleteUserSurveyQuestionAnswer($userSurveyId)
    {
        try {
            $this->userSurveyQuestionAnswer->where('user_survey_id', $userSurveyId)->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting user survey: ' . $e->getMessage());
        }
    }
}
