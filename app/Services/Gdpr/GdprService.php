<?php

namespace App\Services\Gdpr;

use App\Repositories\Gdpr\GdprRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class GdprService implements GdprServiceInterface
{
    private $gdprRepository;

    public function __construct(GdprRepositoryInterface $gdprRepository)
    {
        $this->gdprRepository = $gdprRepository;
    }

    public function get(int $brandId, string $userLanguage)
    {
        $gdprData = $this->gdprRepository->get($brandId, $userLanguage);
        $gdpr = [];

        $parsedGdprFirstEprivacyPage = str_replace(
            $gdprData['placeholders'],
            $gdprData['legalData'],
            $gdprData['firstEprivacyPage']
        );

        $parsedGdprSecondEprivacyPage = str_replace(
            $gdprData['placeholders'],
            $gdprData['legalData'],
            $gdprData['secondEprivacyPage']
        );

        $parsedGdprLegalText = str_replace(
            $gdprData['placeholders'],
            $gdprData['legalData'],
            $gdprData['legalText']
        );

        $gdpr['restrictedPortal'] = $gdprData['restrictedPortal'];
        $gdpr['firstEprivacyPage'] = $parsedGdprFirstEprivacyPage;
        $gdpr['secondEprivacyPage'] = $parsedGdprSecondEprivacyPage;
        $gdpr['legalText'] = $parsedGdprLegalText;

        return $gdpr;
    }

    public function updateOrCreate($hotelId, $gdprInfo)
    {
        try {
            $this->gdprRepository->updateOrCreate($hotelId, $gdprInfo);
        } catch (BadRequestHttpException $e) {
            throw $e;
        }
    }

    public function insertGdprEvents($request)
    {
        $data = $request->data;
        $gdpr_events = $data['gdpr_events'];

        $events = array_map(function ($event) use ($data) {
            $event['user_id'] = $data['user_id'];
            $event['hotel_id'] = $data['hotel_id'];
            $values = implode('","', array_values($event));
            $str = '("' . $values . '")';
            return $str;
        }, $gdpr_events);

        try {
            $this->gdprRepository->insertGdprEvents($events);
        } catch (Exception $e) {
            Log::error("GdprService", [
                "message" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine()
            ]);
        }
    }
}
