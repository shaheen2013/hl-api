<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Aws\EventBridge\EventBridgeClient;
use GuzzleHttp\Exception\GuzzleException;
use Hotelinking\Services\SchemaValidator;
use Hotelinking\Services\ApiGatewayConnection;
use App\User;
use App\Services\Users\UserService;

class EmailResultRevalidatorJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    public $user_id;

    /**
     * Create a new job instance.
     *
     * @param $payload
     */
    public function __construct(int $user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserService $userService)
    {
        // Get user
        $user = $userService->getUser($this->user_id);

        // Perform revalidation
        try {
            // STATUS 200
            // {
            //     "email_quality": "deliverable",
            //     "email_rate": 0.87
            // }
            $result = $this->sendRequest($user->email);
            // Update user with new sendex result
            return $this->updateAndPublishEvent($user, $result);
        } catch (GuzzleException $e) {
            // STATUS 401
            // {
            //     "email_quality": "undeliverable",
            //     "email_rate": 0
            // }
            $response = $e->getResponse() ?? [];
            if (!empty($response)) {
                $content = json_decode($response->getBody()->getContents(), true);
                if ($response->getStatusCode() === 401 && $content["email_quality"] == "undeliverable") {
                    // Update user with new sendex result
                    return $this->updateAndPublishEvent($user, $content);
                }
            }
            // Othercase we have a error
            throw $e;
        }
    }

    /**
     * Send request to lambda validator
     */
    public function sendRequest(string $email)
    {
        $schemaValidator = app(SchemaValidator::class);
        $gateway = new ApiGatewayConnection($schemaValidator);
        // Send request to validate email
        $response = $gateway->sendRequest(
            [
                'email' => $email
            ],
            'emails/validator',
            'POST'
        );

        return json_decode($response->getContents(), true);
    }

    /**
     * Update user with validation results and publish event on eventBridge
     * @param App\User $user
     * @param array $result
     * @return bool
     */
    public function updateAndPublishEvent(User $user, array $result): bool
    {
        // Update user with new sendex result
        $sendex = $result['email_rate'] ?? null;
        $emailResult = $result['email_quality'] ?? null;
        if (!is_null($sendex) && !is_null($emailResult)) {
            $user->sendex = $sendex;
            $user->email_result = $emailResult;
            $user->save();

            // Remove cache for this user
            Cache::tags("user-{$user->id}")->flush();

            // Publish UserEmailRevalidated event on bus
            return $this->publishEventOnBus($user);
        } else {
            throw new \Exception("No sendex and/or email_result from lambda validator response");
        }
    }

    /**
     * Publish event on eventBridge
     * @param App\User $user
     * @return bool
     */
    public function publishEventOnBus(User $user): bool
    {
        $client = new EventBridgeClient([
            'version' => config('services.aws.event-bridge.version'),
            'region' => config('services.aws.event-bridge.region'),
            'credentials' => [
                'key' => config('services.aws.event-bridge.credentials.key'),
                'secret' => config('services.aws.event-bridge.credentials.secret')
            ]
        ]);


        $client->putEvents([
            'Entries' => [
                [
                    'Detail' => json_encode([
                        "user"  => [
                            "id" => $user->id,
                            "email" => $user->email,
                            "email_result" => $user->email_result,
                            "sendex" => $user->sendex,
                        ],
                    ]),
                    'DetailType' => 'user_email_revalidated',
                    'Source' => config('services.aws.event-bridge.source'),
                    'EventBusName' => config('services.aws.event-bridge.name')
                ]
            ],
        ]);
        return true;
    }
}
