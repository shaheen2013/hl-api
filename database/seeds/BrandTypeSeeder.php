<?php

use Illuminate\Database\Seeder;

class BrandTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brand_types = [
            [
                'id'   => 1,
                'type' => 'chain',
                'name' => 'Chain'
            ],

            [
                'id'   => 2,
                'type' => 'hotel',
                'name' => 'Hotel'
            ],
        ];

        foreach ($brand_types as $brand_type) {
            try {
                DB::table('brand_types')->insert($brand_type);
            } catch (\Exception $e) {
                info($e->getMessage());
            }
        }
    }
}
