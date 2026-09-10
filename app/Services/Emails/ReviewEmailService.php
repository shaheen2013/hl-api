<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 04/04/2019
 * Time: 16:41
 */

namespace App\Services\Emails;

use App\Brand;
use Hotelinking\Services\HLConnectionInterface;

class ReviewEmailService extends EmailService
{
    public function __construct(HLConnectionInterface $connection)
    {
        parent::__construct($connection);
    }

    public function sendEmail($originalEntity, $context, $sendDate, $user_id, $brand_id)
    {

        $brand = Brand::findOrFail($brand_id);
        if ($brand->hotel) {
            $payload = ["schema" => "com.hotelinking/Emails/send_review_email/2.0.0",
                "origin" => "HotelinkingApi/",
                "originalEntity" => $originalEntity,
                "eventSource" => url()->current(),
                "context" => empty($context)
                    ? ["brandID" => $brand_id]
                    : (object) $context,
                "payload" => [
                    "sendDate" => $sendDate,
                    "user" => [
                        "id" => (int)$user_id
                    ],
                    "brand" => [
                        "id" => (int)$brand_id,
                        "hotelID" => (int)$brand->hotel->id,
                    ]
                ]
            ];
            return $this->send($payload);
        }
    }
}
