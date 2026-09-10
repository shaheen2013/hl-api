<?php

use App\BrandProductConfig;
use Illuminate\Database\Seeder;
use Symfony\Component\Console\Output\ConsoleOutput;

class AddTimeArrivalForHotelsWithComments extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $output = new ConsoleOutput();

        BrandProductConfig::where(['product_config_id' => 13, 'value' => 1])
        ->get()
        ->each(function ($brandProductConfig) {
            BrandProductConfig::updateOrCreate(
                ['brand_product_id' => $brandProductConfig->brand_product_id, 'product_config_id' => 50],
                ['value' => 1]
            );
        });       
        
        $output->write(PHP_EOL);
    }
}
