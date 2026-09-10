<?php

namespace App\Types\Survey;

use App\Types\Shared\BaseDataType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class SaveSurveyAnswersDataType extends BaseDataType
{
    /** @var int $brandId */
    protected $brandId;

    /** @var int $surveyId */
    protected $surveyId;

    /** @var int $userId */
    protected $userId;

    /** @var int $questionId */
    protected $questionId;

    /** @var int $responseIds */
    protected $responseIds;

    /** @var double $score */
    protected $score;

    /** @var string $comment */
    protected $comment;

    public function __construct(Request $request)
    {
        $this->brandId = $request->brand_id;
        $this->surveyId = $request->survey_id;
        $this->userId = $request->get('user_id');
        $this->questionId = $request->get('question_id');
        $this->responseIds = $request->get('response_id');
        $this->score = $request->get('score');
        $this->comment = trim($this->removeEmoji($request->get('comment')));

        $this->createRules();
    }

    /**
     * @return bool
     */
    public function isInvalidData(): bool
    {
        $data = $this->getAttributes();
        $validator = Validator::make($data, $this->rules);

        return $validator->fails();
    }

    protected function createRules(): void
    {
        $this->rules = [
            'brandId'       => 'numeric|min:1',
            'surveyId'      => 'integer|min:1',
            'userId'        => 'integer|min:1',
            'questionId'    => 'nullable|integer|min:1',
            'responseIds'   => 'nullable|array|min:1',
            'score'         => 'nullable|numeric|between:0,10',
            'comment'       => 'nullable|string'
        ];
    }

    private function removeEmoji($text)
    {
        return preg_replace('/([0-9|#][\x{20E3}])|[\x{00ae}|\x{00a9}|\x{203C}|\x{2047}|\x{2048}|\x{2049}|\x{3030}|\x{303D}|\x{2139}|\x{2122}|\x{3297}|\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', '', $text);
    }
}
