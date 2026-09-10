<?php

namespace App\Jobs;

use Hotelinking\Services\HLConnectionInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessSnsEvent implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $payload;
    public $tries = 1;
    private $integration;

    /**
     * Create a new job instance.
     *
     * @param $payload
     * @param HLConnectionInterface $connection
     */
    public function __construct($payload, HLConnectionInterface $connection)
    {
        $this->payload = json_decode($payload, true);
        $this->integration = $connection;
    }

    /**
     * Execute the job. And it casts the correspondent event depending of the job payload
     *
     * @return void
     */
    public function handle()
    {
        /** Getting event information from the schema name   **/

        $event_type = explode("/", Arr::get($this->payload, 'schema'));
        try {
            $eventPath = $this->buildEventPath($event_type);
            Log::info("Processing $eventPath event", [$this->payload]);
            event(new $eventPath($this->payload, $this->integration));
        } catch (\Throwable $e) {
            //alert msg
            Log::error("Worker Error", ["error" => $e->getMessage(), "payload" => $this->payload, "trace" => $e->getTrace()]);
        }
    }

    public function buildEventPath($schemaName)
    {
        return "App\Events\\" . ucfirst(Str::camel(trim(Arr::get($schemaName, count($schemaName) - 2))));
    }
}
