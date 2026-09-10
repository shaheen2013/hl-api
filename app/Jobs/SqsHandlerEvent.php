<?php

namespace App\Jobs;

use App\Services\Users\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Contracts\Queue\Job as LaravelJob;
use Exception;

class SqsHandlerEvent implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $tries = 1;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Execute the job.
     * @param array $data
     *
     * @return void
     */
    public function handle(LaravelJob $job, array $payload)
    {
        // Check if that is a method with the event name
        $eventType = $payload['detail-type'] ?? null;
        $eventName = Str::camel(trim($eventType));

        if (is_callable(array($this, $eventName))) {
            return $this->{$eventName}($payload);
        } else {
            Log::error("SqsHandlerEvent", ['message' => "$eventName not implemented", 'payload' => $payload]);
            throw new Exception("Method not implemented");
        }
    }


    public function integrationUserUnsubscribed(array $payload)
    {
        try {
            $user = (object) Arr::get($payload, 'detail.user');

            $brands = $this->userService->getUserBrands(Arr::get($payload, 'detail.brands'), $user->id)->pluck('brand_id');

            foreach ($brands as $brand) {
                Log::info('SqsHandlerEvent', ['message' => 'Trying to perform an integrationUserUnsubscribed process', 'brand' => $brand, 'user' => $user]);

                // For the time being we only unsubscribe from notifications by default
                $data = [
                    'unsubscribe_notifications' => true,
                    'unsubscribe_commercial_profile' => false
                ];

                $this->userService->unsubscribe($user->id, $brand, $data);
            }
            Log::info('SqsHandlerEvent', ['message' => 'integrationUserUnsubscribed Processed!', 'payload' => $payload]);
        } catch (Exception $e) {
            Log::error('SqsHandlerEvent', [
                'message' => 'Error trying to perfom integrationUserUnsubscribed process',
                'payload' => $payload,
                'error' => ['message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()]
            ]);
        }
    }
}
