<?php

namespace App\Http\Middleware;

use Closure;
use Aws\Sns\Message;
use Aws\Sns\MessageValidator;
use Aws\Sns\Exception\InvalidSnsMessageException;

class SnsEvents
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config('app.env') === 'testing' || config('app.env') === 'local') {
            $request->snsMessage = ['Message' => json_encode($request->snsMessage)];
            return $next($request);
        }

        $message = Message::fromRawPostData();
        $validator = new MessageValidator();

        try {
            $validator->validate($message);
        } catch (InvalidSnsMessageException $e) {
            return response('SNS Message Validation Error: ' . $e->getMessage(), 404);
        }

        $this->eventSusbscription($message);
        $request->request->add(['snsMessage' => $message]);

        return $next($request);
    }

    private function eventSusbscription(Message $message)
    {
        if ($message['Type'] == 'SubscriptionConfirmation') {
            file_get_contents($message['SubscribeURL']);
        }
    }
}
