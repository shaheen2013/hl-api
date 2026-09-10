<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBrandIdOnNewConnectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_connection', function (Blueprint $table) {
            $table->integer('brand_id')->unsigned()->after('id');
        });
        
        Artisan::call('db:seed', [
            '--class' => 'PopulateBrandForNewConnection',
            '--force' => true
        ]);

        Schema::table('new_connection', function (Blueprint $table) {
            $table->foreign('brand_id')->references('id')->on('brands');
            
            $table->index(['brand_id', 'created_at', 'access_type_id']);
            $table->index(['brand_id', 'created_at', 'device_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_connection', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            
            $table->dropIndex(['brand_id', 'created_at', 'access_type_id']);
            $table->dropIndex(['brand_id', 'created_at', 'device_id']);
        });

        Schema::table('new_connection', function (Blueprint $table) {
            $table->dropColumn('brand_id');
        });
    }
}
