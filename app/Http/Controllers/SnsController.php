<?php

namespace App\Http\Controllers;

use Hotelinking\Services\ApiGatewayConnection;
use Illuminate\Http\Request;
use App\Jobs\ProcessSnsEvent;
use Hotelinking\Services\SchemaValidator;
use Illuminate\Support\Arr;

class SnsController extends Controller
{
    public function queueSns(Request $request)
    {
        $schemaValidator = app(SchemaValidator::class);

        $connection = new ApiGatewayConnection($schemaValidator);

        if (!empty($request->snsMessage)) {
            ProcessSnsEvent::dispatch(Arr::get($request->snsMessage, 'Message'), $connection);
        }

        return response(null, 200);
    }
}
