<?php

namespace App\Types\Users;

use App\Types\Shared\BaseDataType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class BulkUnsubscribeDataType extends BaseDataType
{
    /** @var int $brandId */
    protected $brandId;

    /** @var int $userIds */
    protected $userIds;

    /** @var object $errors */
    protected $errors;

    public function __construct(Request $request)
    {
        $this->brandId = $request->brand_id;
        $this->userIds = $request->get('user_ids');

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
            'brandId'       => 'required|numeric|min:1',
            'userIds'        => 'required|array|min:1',
            'userIds.*'      => 'required|integer|min:1',
        ];
    }
}
