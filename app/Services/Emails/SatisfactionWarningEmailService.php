<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 05/04/2019
 * Time: 13:41
 */

namespace App\Services\Emails;

use App\Brand;
use App\ConnectionHistory;
use App\User;
use Carbon\Carbon;
use Hotelinking\Services\HLConnectionInterface;

class SatisfactionWarningEmailService extends EmailService
{
    /**
     * SatisfactionWarningEmailService constructor.
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
     * @param User $user
     * @param Brand $brand
     * @param $surveyAnswer
     * @param $minScore
     * @param $brandWarningEmail
     * @param $customizedActive
     * @param $score
     * @param $comment
     * @param $customizedType
     * @param $answers
     */
    public function sendEmail(
        $originalEntity,
        $context,
        $sendDate,
        User $user,
        Brand $brand,
        $surveyAnswer,
        $minScore,
        $brandWarningEmail,
        $customizedActive,
        $score,
        $comment,
        $customizedType,
        $answers
    ) {
        $warningsEmails =  explode(",", $brandWarningEmail);
        if ($brand->hotel) {
            $visit = ConnectionHistory::where('id_hotel', $brand->hotel->id)
                ->where('id_user', $user->id)
                ->orderBy('id', 'desc')
                ->first();

            $lastLogin = $surveyAnswer->fecha_creado;
            if ($visit) {
                $lastLogin = $visit->last_login;
            }
            $user->load('language');

            foreach ($warningsEmails as $email) {
                $payload = ["schema" => "com.hotelinking/Emails/send_satisfaction_warning_email/2.0.0",
                "origin" => "HotelinkingApi/",
                "originalEntity" => $originalEntity,
                "eventSource" => url()->current(),
                "context" =>  empty($context)
                    ? ["brandID" => $brand->id]
                    : (object) $context,
                "payload" => [
                    "user" => [
                        "id" => (int) $user->id,
                        "name" => $user->name,
                        "email" => $user->email,
                        "lang" => $user->language->name ?? 'Unknown',
                        "gender" => $user->gender,
                        "birthDate" => Carbon::parse($user->birthday)->format('Y-m-d'),
                        "firstLogin" => Carbon::parse($surveyAnswer->fecha_creado)->format('Y-m-d'),
                        "lastLogin" => Carbon::parse($lastLogin)->format('Y-m-d'),
                        "roomID" => $surveyAnswer->id_room
                    ],
                    "brand" => [
                        "id" => (int) $brand->id,
                        "hotelID" => (int) $brand->hotel->id,
                        "email" => trim($email),
                        "name" => $brand->hotel->name,
                        "minScore" => $minScore
                    ],
                    "survey" => [
                        "score" => floatval($score),
                        "comment" => $comment,
                        "createdAt" => Carbon::parse($surveyAnswer->fecha_creado)->format('Y-m-d'),
                        "sentAt" => Carbon::parse($sendDate)->format('Y-m-d') ,
                        "filledAt" => Carbon::now()->format('Y-m-d'),
                        "customizedActive" => $customizedActive ? true : false,
                        "customizedType" => $customizedType,
                        "answers" => $answers,
                    ]
                ]];

                $this->send($payload);
            }
        }
    }
}
