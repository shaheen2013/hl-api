<?php

use App\HotelReview;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Database\Seeder;

class PopulateReviewSendTypeBasedOnIgnoreRating extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $output = new ConsoleOutput();
        $progress = new ProgressBar($output, HotelReview::count());
        $progress->start();


        HotelReview::chunk(1000, function($hotelReviews) use ($progress)
        {
            foreach ($hotelReviews as $hotelReview)
            {
                $hotelReview->send_type = $hotelReview->ignoreRating ? "after_wifi" : "after_satisfaction";
                $hotelReview->save();

                $progress->advance();
            }            
        });
        
        $progress->finish();
        $output->write(PHP_EOL);
    }
}
