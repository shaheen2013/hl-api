<?php

namespace App\Jobs;

use App\Services\SatisfactionService;
use App\Services\BrandService;
use App\Types\Survey\SurveyExportDataType;
use App\Types\Survey\GetSurveyDataType;
use App\Exceptions\InvalidRequestException;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Aws\EventBridge\EventBridgeClient;

class ProcessSurveyExport implements ShouldQueue
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
    public $timeout = 300;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $brandID, SurveyExportDataType $data)
    {
        $this->brandID = $brandID;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SatisfactionService $satisfactionService, BrandService $brandService)
    {
        Log::info("SURVEY REPORT INFO: Starting survey report process", ["brand" => $this->brandID]);

        try {
            $headers =  [
                "Hotel Name",
                "Guest",
                "Room Id",
                "Country",
                "Birthdate",
                "Sex",
                "Question",
                "Rating/Answer",
                "Comment/Open question",
                "Category",
                "Time-lapse to Response",
                "Send Date",
                "Filled At",
                "Incidents",
                "Assisted At",
                "Check In",
                "Check out",
                "Reservation Channel"
            ];

            $brand = $brandService->getBrand($this->brandID);
            $brandType = ($brand->hotel_id) ? 'id_hotel' : 'id_cadena';
            $brandTypeId = ($brand->hotel_id) ? $brand->hotel_id : $brand->chain_id;

            $data = new GetSurveyDataType(new Request($this->data->request), $brandType, $brandTypeId);

            if ($data->isInvalidData()) {
                throw new InvalidRequestException('Bad fields in survey export job', $data->getAttributes(), 400);
            }

            $localFile = $satisfactionService->getSurveyReport($this->brandID, $data, $headers);

            Log::info("SURVEY REPORT INFO: report generated and ready to send", ["brand" => $this->brandID]);

            $s3Storage = Storage::disk('s3');
            $path = '/surveys/' . $brand->uuid . '/HotelinkingSurveyReport_' . date('Y-m-d') . '_' . str_replace('.', '-', microtime(true)) . '.csv';

            $saved = $s3Storage->putFileAs('/', new File($localFile), $path);

            Log::info("SURVEY REPORT INFO: report saved on s3", ["brand" => $this->brandID, "saved" => $saved]);

            if (file_exists($localFile)) {
                unlink($localFile);
            }

            $this->sendSurveyReportCreatedEvent($path);
        } catch (\Throwable $e) {
            Log::error("Worker Error", ["error" => $e]);
        }
    }

    private function sendSurveyReportCreatedEvent($path)
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
                        'DetailType' => 'survey_report_generated',
                        'Source' => config('services.aws.event-bridge.source'),
                        'EventBusName' => config('services.aws.event-bridge.name')
                    ]
                ],
            ]);
        }
    }
}
