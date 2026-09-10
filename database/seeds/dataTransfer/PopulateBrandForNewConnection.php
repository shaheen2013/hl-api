<?php

use App\Connection;

use Illuminate\Database\Seeder;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class PopulateBrandForNewConnection extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $output = new ConsoleOutput();
        $progress = new ProgressBar($output, Connection::where(['brand_id' => 0])->count());
        $progress->start();


        Connection::where(['brand_id' => 0])->chunk(1000, function($connections) use ($progress)
        {
            foreach ($connections as $connection)
            {
                $connection->brand_id = $connection->visit->userBrand->brand_id;
                $connection->save();

                $progress->advance();
            }            
        });
        
        $progress->finish();
        $output->write(PHP_EOL);
    }
}
