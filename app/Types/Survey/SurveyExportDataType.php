<?php

namespace App\Types\Survey;

use App\Types\Shared\BaseDataType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SurveyExportDataType extends BaseDataType
{
    /** @var array $emailList */
    protected $emails;

    /** @var string $search */
    protected $search;

    /** @var string $from */
    protected $from;

    /** @var string $to */
    protected $to;

    /** @var string $sortField */
    protected $sortField;

    /** @var string ?sortOrder */
    protected $sortOrder;

    protected $request;

    /**
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->emails = $request->get('emails');

        $this->search = $request->get('search');

        $this->from = $request->get('from');

        $this->to = $request->get('to');

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
            'emails'        => 'required|array|min:1',
            'emails.*'      => 'required|email',
            'search'        => 'nullable|string',
            'from'          => 'nullable|date',
            'to'            => 'nullable|date',
            'sortField'     => 'nullable|string',
            'sortOrder'     => 'nullable|string|in:asc,desc',
        ];
    }
}
