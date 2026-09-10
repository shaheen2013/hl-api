<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RemoveScanOnReceptionRowFromProductConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('brand_product_config')->where('product_config_id', 46)->delete();
        DB::table('product_config')->where('id', 46)->where('label', 'scan_on_reception')->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $exists = DB::table('product_config')->where('id', 46)->exists();
        if(!$exists){
            DB::table('product_config')->insert([
                'id' => 46,
                'product_id' => 21, 
                'label' => 'scan_on_reception',
                'type' => 'boolean',
            ]);
        }
        // Note: brand_product_config records are not restored
    }
}
