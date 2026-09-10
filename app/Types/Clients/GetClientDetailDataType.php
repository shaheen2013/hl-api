<?php

namespace App\Types\Clients;

use App\Types\Shared\BaseDataType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GetClientDetailDataType extends BaseDataType
{
    /** @var string $brandId */
    protected $brandId;

    /** @var string $userId */
    protected $userId;

    /** @var object $errors */
    protected $errors;

    /**
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->brandId = $request->brand_id;
        $this->userId = $request->user_id;

        $this->createRules();
    }

    /**
     * @return bool
     */
    public function isInvalidData(): bool
    {
        $data = $this->getAttributes();
        $validator = Validator::make($data, $this->rules);

        if ($validator->fails()) {
            $this->errors = $validator->errors();
        }

        return $validator->fails();
    }

    public function getErrors()
    {
        return $this->errors;
    }

    protected function createRules(): void
    {
        $this->rules = [
            'brandId'   => 'required|numeric|min:1',
            'userId'    => 'required|numeric|min:1'
        ];
    }
}
