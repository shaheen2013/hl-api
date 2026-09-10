<?php

namespace App\Types\Gdpr;

use App\Types\Shared\BaseDataType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class GdprDataType extends BaseDataType
{
    /** @var string $hotelId */
    protected $hotelId;

    /** @var string $chainId */
    protected $chainId;

    /** @var Array $info */
    protected $info;

    /**
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->hotelId = $request->hotelId;

        $this->chainId = $request->chainId;

        $this->info = $request->info;

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
            'hotelId'       => 'nullable|numeric',
            'chainId'       => 'nullable|numeric',
            'info'          => 'required|array'
        ];
    }
}
