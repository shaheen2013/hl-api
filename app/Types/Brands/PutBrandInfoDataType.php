<?php

namespace App\Types\Brands;

use App\Types\Shared\BaseDataType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PutBrandInfoDataType extends BaseDataType
{
    /** @var Array $brand */
    protected $brand;

    /**
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->brand = $request->brand;

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
            'brand'                    => 'required|array',
            'brand.id'                 => 'required|integer',
            'brand.name'               => 'nullable|string',
            'brand.logo'               => 'nullable|string',
            'brand.background_color'   => 'nullable|string',
            'brand.background_image'   => 'nullable|string',
            'brand.place_country'      => 'nullable|string',
            'brand.sending_email'      => 'nullable|string',
            'brand.time_zone'          => 'nullable|string',
            'brand.products'           => 'nullable|array'
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
