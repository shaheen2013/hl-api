<?php

use App\Connection;

use Illuminate\Database\Seeder;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class PopulateDeviceBrandOnConnectionTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $output = new ConsoleOutput();
        $progress = new ProgressBar($output, Connection::whereNull("device_brand")->count());
        $progress->start();


        Connection::whereNull("device_brand")->chunkById(1000, function($connections) use ($progress)
        {
            foreach ($connections as $connection)
            {
                $connection->device_brand = $connection->device->device_brand;
                $connection->save();

                $progress->advance();
            }            
        });
        
        $progress->finish();
        $output->write(PHP_EOL);
    }
}
