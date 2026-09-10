<?php

use App\Visit;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PopulateNewVisitReservationColumnWithConnectionData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $output = new ConsoleOutput();
        $progress = new ProgressBar($output, Visit::count());
        $progress->start();


        Visit::chunk(10000, function($visits) use ($progress)
        {
            foreach ($visits as $visit)
            {
                $connections =  $visit->connections()
                    ->orderBy('created_at', 'desc')
                    ->take(50)
                    ->get()
                    ->map(function ($connection) {
                        $device = $connection->device;

                        return [
                            "date"          => $connection->created_at,
                            "access_code"   => $connection->accessCode,
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
                    $visit->reservation = DB::raw("JSON_SET(COALESCE(reservation, '{}'), '$.connections', CAST('".addslashes(json_encode($connections->toArray()))."' AS JSON))");
                    $visit->save();
                }
                
                
                $progress->advance();
            }            
        });
        
        $progress->finish();
        $output->write(PHP_EOL);
    }
}
