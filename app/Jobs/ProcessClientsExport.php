<?php

namespace App\Jobs;

use App\Services\Clients\ClientService;
use App\Services\BrandService;
use App\Types\Clients\ClientsExportDataType;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Aws\EventBridge\EventBridgeClient;
use Illuminate\Http\File;

class ProcessClientsExport implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $brandID;
    protected $data;

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
    public function __construct(int $brandID, ClientsExportDataType $data)
    {
        $this->brandID = $brandID;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ClientService $clientService, BrandService $brandService)
    {
        Log::info("CLIENT REPORT INFO: Starting client report process", ["brand" => $this->brandID]);

        try {
            $headers = [
                'Complete Name',
                'First name',
                'Last name',
                'Email',
                'Hotel Name',
                'Birthday',
                'Country',
                'Language',
                'Sex',
                'Last Login',
                'First Login',
                'Reservation Date',
                'Check-in',
                'Check-out',
                'Channel',
                'Agency',
                'PMS ID',
                'Hotel Visits',
                'Chain Visits',
                'Subscribed',
                'Room',
                'Satisfaction',
                'Comments',
            ];

            if ($this->data->phone_active) {
                $headers[] = 'Phone Number';
            }

            if ($this->data->commercial_profile) {
                $headers[] = 'Commercial Profile';
            }

            $brand = $brandService->getBrand($this->brandID);
            $localFile = $clientService->getBrandReportClients($brand, $this->data, $headers);

            Log::info("CLIENT REPORT INFO: report generated and ready to send", ["brand" => $this->brandID]);

            $s3Storage = Storage::disk('s3');
            $path = '/clients/' . $brand->uuid . '/HotelinkingClientReport_' . date('Y-m-d') . '_' . str_replace('.', '-', microtime(true)) . '.csv';

            $saved = $s3Storage->putFileAs('/', new File($localFile), $path);

            Log::info("CLIENT REPORT INFO: report saved on s3", ["brand" => $this->brandID, "saved" => $saved]);

            if (file_exists($localFile)) {
                unlink($localFile);
            }

            $this->sendClientReportCreatedEvent($path);
        } catch (\Throwable $e) {
            Log::error("Worker Error", ["error" => $e]);
        }
    }

    private function sendClientReportCreatedEvent($path)
    {

        if (config('app.env') != "testing") {
            $client = EventBridgeClient::factory(array(
                'version' => config('services.aws.event-bridge.version'),
                'region' => config('services.aws.event-bridge.region'),
                'credentials' => array(
                    'key' => config('services.aws.event-bridge.credentials.key'),
                    'secret' => config('services.aws.event-bridge.credentials.secret')
                )
            ));

            $result = $client->putEvents([
                'Entries' => [
                    [
                        'Detail' => json_encode([
                            "brand"  => [
                                "id" => $this->brandID,
                            ],
                            "emails"    => $this->data->emails,
                            "path"      => $path
                        ]),
                        'DetailType' => 'client_report_generated',
                        'Source' => config('services.aws.event-bridge.source'),
                        'EventBusName' => config('services.aws.event-bridge.name')
                    ]
                ],
            ]);
        }
    }
}
