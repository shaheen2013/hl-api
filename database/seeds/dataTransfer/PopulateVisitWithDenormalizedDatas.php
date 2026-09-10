<?php

use App\Visit;
use Illuminate\Database\Seeder;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class PopulateVisitWithDenormalizedDatas extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $output = new ConsoleOutput();
        $progress = new ProgressBar($output, Visit::where(['brand_id' => 0])->orWhere(['user_id' => 0])->count());
        $progress->start();


        Visit::where(['brand_id' => 0])->orWhere(['user_id' => 0])->chunkById(10000, function($visits) use ($progress)
        {
            foreach ($visits as $visit)
            {
                $visit->brand_id = $visit->userBrand->brand_id;
                $visit->user_id = $visit->userBrand->user_id;

                $visit->save();
                $progress->advance();
            }            
        });
        
        $progress->finish();
        $output->write(PHP_EOL);
    }
}
