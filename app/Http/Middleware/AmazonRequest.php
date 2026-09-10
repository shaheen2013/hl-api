<?php

namespace App\Http\Middleware;

use Closure;

class AmazonRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($this->isLocalOrTest()) {
            // Add externalApi to false in case of testing and local
            $request->attributes->add(['externalApi' => false]);
            return $next($request);
        }

        $headers = $request->header();
        $amazonApiId = $headers['x-amzn-apigateway-api-id'][0] ?? null;


        if (!$amazonApiId || ($amazonApiId != config('services.aws.gateway.id') && $amazonApiId != config('external-api.id'))) {
            return response('Forbidden', 403);
        }

        // Add attribute to request to identify if the request is from internal or external
        $request->attributes->add(['externalApi' => $this->isExternalApiRequest($amazonApiId)]);

        return $next($request);
    }

    /**
     * Check if the request come from internal or external apiGateway
     *
     * @param string $amazonApiId ApiGateway id passed on headers x-amzn-apigateway-api-id when the reqeuest pass on aws apiGateway
     *
     * @return bool
     */
    public function isExternalApiRequest(string $amazonApiId): bool
    {
        return $amazonApiId == config('external-api.id');
    }

    /**
     * Check if is a test or local environment
     *
     * @return bool
     */
    public function isLocalOrTest(): bool
    {
        return config('app.env') === 'testing' || config('app.env') === 'local';
    }
}
