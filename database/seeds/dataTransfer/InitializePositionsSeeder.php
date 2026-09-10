<?php

use Illuminate\Database\Seeder;

class InitializePositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands = DB::table('category')->distinct()->pluck('brand_id');
        foreach ($brands as $brandId) {
            $categories = DB::table('category')->where('brand_id', $brandId)->orderBy('id')->get();
            foreach ($categories as $index => $category) {
                DB::table('category')->where('id', $category->id)->update(['position' => $index + 1]);
            }
        }

        $categories = DB::table('category')->pluck('id');
        foreach ($categories as $categoryId) {
            $questions = DB::table('question')->where('category_id', $categoryId)->orderBy('id')->get();
            foreach ($questions as $index => $question) {
                DB::table('question')->where('id', $question->id)->update(['position' => $index + 1]);
            }
        }
    }
}
