<?php

namespace App\Console\Commands;

use App\Brand;
use App\Visit;

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Support\Facades\DB;

class RecoverRoomNumberOnVisitTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotelinking:recover-room-number {--accountId=} {--brandId=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recover the room number from the connection table to put it in the new_visit table and improve performance.';
    protected $brandService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $accountId = $this->option('accountId');
        $brandId = $this->option('brandId');

        if (!$accountId && !$brandId) {
            $this->getOutput()->error("No brandId or accountId defined in the command");
            return 0;
        }

        if ($accountId) {
            $brands = Brand::where(['parent_id' => $accountId])->pluck('id')->toArray();
        } else {
            $brands = Brand::where(['id' => $brandId])->pluck('id')->toArray();
        }


        if (empty($brands)) {
            $this->getOutput()->error("No brands found in database with this ID");
            return 0;
        }

        $output = new ConsoleOutput();
        $progress = new ProgressBar($output, Visit::whereIn("brand_id", $brands)->count());
        $progress->start();


        Visit::whereIn("brand_id", $brands)->chunk(10000, function ($visits) use ($progress) {
            foreach ($visits as $visit) {
                $connections =  $visit->connections()
                    ->orderBy('created_at', 'desc')
                    ->take(50)
                    ->get()
                    ->map(function ($connection) {
                        $device = $connection->device;

                        return [
                            "date"          => $connection->created_at,
                            "access_code"   => $connection->access_code,
                            "source"        => $connection->accessType->name,
                            "device" => [
                                "mac_address"       => $device->mac_address,
                                "family"            => $device->device_family,
                                "brand"             => $device->device_brand,
                                "model"             => $device->device_model,
                                "operating_system"  => $connection->operating_system . ' ' . $connection->operating_system_version
                            ]
                        ];
                    });

                // Update visit reservation with new connections data
                if (!$connections->isEmpty()) {
                    $visit->reservation = DB::raw("JSON_SET(COALESCE(reservation, '{}'), '$.connections', CAST('" . addslashes(json_encode($connections->toArray())) . "' AS JSON))");
                    $visit->save();
                }
                
                
                $progress->advance();
            }
        });
        
        $progress->finish();
        $output->write(PHP_EOL);
    }
}
