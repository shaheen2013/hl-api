<?php

namespace App\Types\Accounts;

use App\Types\Shared\BaseDataType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PutAccountInfoDataType extends BaseDataType
{
    /** @var Array $account */
    protected $account;

    /**
     * @param Request $request
     * @return void
     */

    public function __construct(Request $request)
    {
        $this->account = $request->account;

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
            'account' => 'required|array',
            'account.account_name' => 'nullable|string',
            'account.description' => 'nullable|string',
            'account.contact_email' => 'nullable|string',
            'account.contact_tel' => 'nullable|string',
            'account.stay_time' => 'nullable|integer',
            'account.loyalty_min_visits' => 'nullable|integer',
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
