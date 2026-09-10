<?php

namespace App\Types\Gdpr;

use App\Types\Shared\BaseDataType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class GdprEventsDataType extends BaseDataType
{
    /** @var Array $data */
    protected $data;

    /**
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->data = $request->data;

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
            'data'             => 'required|array|size:3',
            'data.user_id'     => 'required|integer',
            'data.hotel_id'    => 'required|integer',
            'data.gdpr_events' => 'required|array'
        ];
    }
}
