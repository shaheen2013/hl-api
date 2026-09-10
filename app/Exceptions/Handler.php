<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable $exception
     * @return void
     * @throws Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Throwable $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        // Return HTML if it's not in the path groups (events, api)
        if (!$request->is('api/*') && !$request->is('events/*')) {
            return parent::render($request, $exception);
        }

        // Customize laravel response exception
        if ($exception instanceof ModelNotFoundException) {
            abort(404, $exception->getMessage());
        }

        // Define the response
        $response = [
            'errors' => $this->getErrorMessage($request, $exception)
        ];

        // If the app is in debug mode
        if (config('app.debug')) {
            // Add the exception class name, message and stack trace to response
            $response['exception'] = get_class($exception); // Reflection might be better here
            $response['message'] = $exception->getMessage();
            $response['trace'] = $exception->getTrace();
        }

        // Default response of 400
        $status = method_exists($exception, 'getStatusCode') ?
            $exception->getStatusCode() :
            (!empty($exception->getCode()) ? $exception->getCode() : 400);

        // Return a JSON response with the response array and status code
        return response()->json($response, $status);
    }

    /**
     * Define error message in function of the origin apigateway
     *
     * @param Request $request
     * @param Exception $exception
     *
     * @return string
     */
    public function getErrorMessage($request, $exception): string
    {
        // Is a request from external
        $isExternalApi = $request->get('externalApi') ?? false;

        if ($isExternalApi) {
            // Check if has a external custom message, if not send the default message
            return method_exists($exception, 'getExternalMessage') ? $exception->getExternalMessage() : config('external-api.default-error-message');
        }
        return $exception->getMessage();
    }
}
