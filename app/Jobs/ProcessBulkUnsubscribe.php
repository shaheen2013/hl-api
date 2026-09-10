<?php

namespace App\Jobs;

use App\Services\Users\UserService;
use App\Types\Users\BulkUnsubscribeDataType;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class ProcessBulkUnsubscribe implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $brandId;
    protected $userIds;
    protected $gatewayConnection;
    protected $satisfactionService;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 3600;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $brandId, array $userIds)
    {
        $this->brandId = $brandId;
        $this->userIds = $userIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserService $userService)
    {
        try {
            $data = [
                'unsubscribe_notifications' => true,
                'unsubscribe_commercial_profile' => false
            ];

            foreach ($this->userIds as $userId) {
                $userService->unsubscribe($userId, $this->brandId, $data);
            }
        } catch (\Throwable $e) {
            Log::error("Worker Error", ["error" => $e]);
        }
    }
}
