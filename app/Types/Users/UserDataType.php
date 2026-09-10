<?php

namespace App\Types\Users;

use App\Types\Shared\BaseDataType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserDataType extends BaseDataType
{
    /** @var Array $data */
    protected $data;

    /** @var object $errors */
    protected $errors;

    /**
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->data = $this->sanitizeData($request->data);
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
        $cjkCharacters = $this->detectCjkCharacters(data_get($this->data, 'first_name') . data_get($this->data, 'last_name'));
        $specialLangs = $this->detectSpecialLangs(data_get($this->data, 'lang'));
        $nameLength =  $specialLangs || $cjkCharacters ? 1 : 2;

        $this->rules = [
            'data'                  => 'required|array|min:14',
            'data.email'            => 'required|string|email',
            'data.first_name'       => 'required|string|min:' . $nameLength,
            'data.last_name'        => 'required|string|min:' . $nameLength,
            'data.lang'             => 'required|string|min:2',
            'data.gender'           => 'nullable|string',
            'data.birthday'         => 'required|date_format:Y-m-d',
            'data.locale'           => 'required|string|min:2',
            'data.document_number'  => 'nullable|string',
            'data.email_result'     => 'required|string',
            'data.country'          => 'required|string',
            'data.sendex'           => 'required|numeric|between:0,1',
            'data.unsubscribed'     => 'required|integer|in:0,1',
            'data.customer'         => 'nullable|integer|in:0,1',
            'data.pms_id'           => 'nullable|string',
            'data.facebook_id'      => 'sometimes|nullable|integer',
            'data.facebook_img'     => 'sometimes|nullable|string|url',
            'data.facebook_friends' => 'sometimes|nullable|integer|min:0',
            'data.origin'           => 'nullable|string|in:form,google,facebook',
            'data.commercial_profile' => 'required|integer|in:0,1',
        ];
    }

    private function sanitizeData($data)
    {
        $stripTagsOn = ["email",  "birthday", "facebook_img", "first_name", "last_name", "lang", "gender", "locale", "document_number", "country", "pms_id"];
        $addSlashesTo = ["pms_id"];
        $data = collect($data)->map(function ($value, $key) use ($stripTagsOn, $addSlashesTo) {
            if (in_array($key, $stripTagsOn)) {
                $value = strip_tags($value);
            }
            if (in_array($key, $addSlashesTo)) {
                $value = addslashes($value);
            }
            return $value;
        });
        return $data->all();
    }

    // Detect if name contains chinese, japanese or korean characters
    private function detectCjkCharacters($name)
    {
        return preg_match("/\p{Han}+/u", $name) || preg_match('/[\x{4E00}-\x{9FBF}\x{3040}-\x{309F}\x{30A0}-\x{30FF}]/u', $name) || preg_match('/[\x{3130}-\x{318F}\x{AC00}-\x{D7AF}]/u', $name);
    }

    private function detectSpecialLangs($lang)
    {
        return $lang == "zh" || $lang == "ko" || $lang == "ja";
    }
}
