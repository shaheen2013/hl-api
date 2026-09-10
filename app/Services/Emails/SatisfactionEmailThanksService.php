<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 05/04/2019
 * Time: 13:41
 */

namespace App\Services\Emails;

use App\Brand;
use App\BrandUrlLanguage;
use App\Language;
use App\User;
use Hotelinking\Services\HLConnectionInterface;

class SatisfactionEmailThanksService extends EmailService
{
    /**
     * SatisfactionEmailThanksService constructor.
     * @param HLConnectionInterface $connection
     */
    public function __construct(HLConnectionInterface $connection)
    {
        parent::__construct($connection);
    }

    /**
     * @param $originalEntity
     * @param $context
     * @param $sendDate
     * @param $user_id
     * @param $brand_id
     * @param $satisfied
     * @return mixed
     */
    public function sendEmail($originalEntity, $context, $sendDate, $user_id, $brand_id, $satisfied)
    {

        $brand = Brand::findOrFail($brand_id);
        $hotel = $brand->hotel;
        $user = User::find($user_id);

        $language = Language::where('name', '=', $user->lang)->first();

        $languageId = $language ?
            $language->id :
            Language::where('name', '=', 'en')->first();

        $brandUrlLanguage = BrandUrlLanguage::where('brand_id', '=', $brand->id)
            ->where('language_id', '=', $languageId)
            ->first();

        if ($brand->hotel) {
            $payload = ["schema" => "com.hotelinking/Emails/send_satisfaction_thanks_email/1.0.0",
                "origin" => "HotelinkingApi/",
                "originalEntity" => $originalEntity,
                "eventSource" => url()->current(),
                "context" => empty($context)
                    ? ["brandID" => $brand_id]
                    : (object) $context,
                "payload" => [
                    "sendDate" => $sendDate,
                    "user" => [
                        'id' => (int) $user_id,
                        'satisfied' => $satisfied
                    ],
                    "brand" => [
                        "id" => (int) $brand_id,
                        "hotelID" => (int) $hotel->id,
                        "url" => (string) data_get($brandUrlLanguage, 'url')
                    ]
                ]
            ];

            return $this->send($payload);
        }
    }
}
