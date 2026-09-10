<?php

use App\HotelSatisfaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PopulateReviewAverageScore extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
    */
    public function run()
    {

        HotelSatisfaction::whereNotNull('puntMin')->update(['review_average_score' => DB::raw("`puntMin`")]);

        // filter_warning to 0 means the bit is activated in front
        HotelSatisfaction::where('filter_warning', '=', '0')->update(['review_average_score' => 0]);

    }
}
