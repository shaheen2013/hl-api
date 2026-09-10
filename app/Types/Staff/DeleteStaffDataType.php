<?php

namespace App\Types\Staff;

use App\Types\Shared\BaseDataType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeleteStaffDataType extends BaseDataType
{
    /** @var int $brandId */
    protected $brandId;

    /** @var string $brandType */
    protected $brandType;

    /** @var int $staffId */
    protected $staffId;

    /**
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->brandId = $request->brand_id;

        $this->brandType = $request->get('brand_type');

        $this->staffId = $request->staff_id;

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
            'brandId'   => 'required|numeric|min:1',
            'brandType' => 'required|string|in:hotel,chain',
            'staffId'   => 'required|numeric|min:1',
        ];
    }
}
