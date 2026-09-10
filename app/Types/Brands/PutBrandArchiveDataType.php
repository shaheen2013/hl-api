<?php

namespace App\Types\Brands;

use App\Types\Shared\BaseDataType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PutBrandArchiveDataType extends BaseDataType
{
    /** @var integer $brand */
    protected $brand_id;

    /** @var integer $status */
    protected $status;

    /**
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        
        $this->status = $request->status;
        $this->brand_id = $request->route('brand_id');

        $this->createRules();
    }

    /**
     * @return bool
     */
    public function isInvalidData(): bool
    {
        $data = $this->getAttributes();
        $validator = Validator::make($data, $this->rules);

        $this->validatorMessages = $validator->errors()->all();

        return $validator->fails();
    }

    protected function createRules(): void
    {
        $this->rules = [
            'status'                    => 'required|integer',
            'brand_id'                  => 'required|integer'
        ];
    }

    /**
     * @return array
     */
    public function getValidatorMessages()
    {
        return $this->validatorMessages;
    }
}
