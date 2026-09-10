<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Http\Resources\Gdpr\GdprResource;
use App\Types\Gdpr\GdprDataType;
use App\Services\Gdpr\GdprServiceInterface;
use App\Types\Gdpr\GdprEventsDataType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class GdprController extends Controller
{
    private $gdprService;
    public function __construct(GdprServiceInterface $gdprService)
    {
        $this->gdprService = $gdprService;
    }

    public function get(Request $request, int $brandId)
    {
        $userLanguage = $request->input('lang');

        $serviceResponse = new GdprResource($this->gdprService->get($brandId, $userLanguage));

        return $serviceResponse->response()->setStatusCode(200);
    }

    public function store(GdprDataType $request, int $brandId)
    {
        try {
            if (!$request->isInvalidData()) {
                $this->gdprService->updateOrCreate($brandId, $request);
                return response([], 204);
            }
            Log::error('GdprController', ['error' => 'GdprDataTypeValidationError', 'brandId' => $brandId]);
            throw new ValidationException($request);
        } catch (BadRequestHttpException $e) {
            Log::error('GdprController', ['error' => $e->getMessage(), 'brandId' => $brandId]);
            throw $e;
        }
    }

    public function insertGdprEvents(GdprEventsDataType $request, int $brand_id)
    {
        if (!$request->isInvalidData()) {
            $this->gdprService->insertGdprEvents($request);
            return response([], 201);
        }
        Log::error('GdprController', ['message' => 'GdprEventsDataTypeValidationError', 'brand_id' => $brand_id]);
        throw new InvalidRequestException('Bad fields in request body', $request->getAttributes(), 400);
    }
}
