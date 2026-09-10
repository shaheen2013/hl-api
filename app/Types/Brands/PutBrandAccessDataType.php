<?php

namespace App\Types\Brands;

use App\Types\Shared\BaseDataType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PutBrandAccessDataType extends BaseDataType
{
    /** @var string $brandId */
    protected $brandId;

    /** @var string $accessTypeId */
    protected $accessTypeId;

    /** @var string $active */
    protected $active;

    /**
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->brandId = $request->brand_id;

        $this->accessTypeId = $request->access_type_id;

        $this->active = $request->get('active');

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
            'brandId'       => 'required|numeric|min:1',
            'accessTypeId'  => 'required|numeric|min:1',
            'active'        => 'required|bool'
        ];
    }
}
