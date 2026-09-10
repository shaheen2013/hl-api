<?php

namespace App\Types\Survey;

use App\Types\Shared\BaseDataType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GetSurveyDataType extends BaseDataType
{
    /** @var string $brandType */
    public $brandType;

    /** @var string $brandTypeId */
    public $brandTypeId;

    /** @var int $satisfactionId */
    public $satisfactionId;

    /** @var string $lang */
    public $lang;

    /** @var int $score */
    public $score;

    /** @var int $days */
    public $days;

    /** @var string $search */
    public $search;

    /** @var string $from */
    public $from;

    /** @var string $to */
    public $to;

    /** @var int $limit */
    public $limit;

    /** @var string $sortField */
    public $sortField;

    /** @var string ?sortOrder */
    public $sortOrder;

    /** @var string request */
    public $request;

    /**
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request, $brandType, $brandTypeId)
    {
        $this->brandType = $brandType;

        $this->brandTypeId = $brandTypeId;

        $this->satisfactionId = $request->satisfaction_id ?? null;

        $this->lang = $request->get('lang');

        $this->score = $request->get('score');

        $this->days = $request->get('days');

        $this->search = $request->get('search');

        $this->from = $request->get('from');

        $this->to = $request->get('to');

        $this->limit = $request->get('limit');

        $this->sortField = $request->get('order');

        $this->sortOrder = $request->get('sort') ?
            strtolower($request->get('sort')) :
            'desc';

        $this->request = $request->all();

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
            'brandType'         => 'required|string|in:id_hotel,id_cadena',
            'brandTypeId'       => 'required|integer',
            'satisfactionId'    => 'nullable|integer',
            'lang'              => 'nullable|string',
            'score'             => 'nullable|integer',
            'days'              => 'nullable|integer',
            'limit'             => 'nullable|integer',
            'search'            => 'nullable|string',
            'from'              => 'nullable|date',
            'to'                => 'nullable|date',
            'sortField'         => 'nullable|string',
            'sortOrder'         => 'nullable|string|in:asc,desc',
        ];
    }
}
