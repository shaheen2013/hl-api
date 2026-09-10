<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;


class AddDenormalizeOnNewVisitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_visit', function (Blueprint $table) {
            $table->integer('brand_id')->unsigned();
            $table->integer('user_id');
        });
        
        Artisan::call('db:seed', [
            '--class' => 'PopulateVisitWithDenormalizedDatas',
            '--force' => true
        ]);

        Schema::table('new_visit', function (Blueprint $table) {
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('user_id')->references('id')->on('users');
            
            $table->index(['brand_id', 'check_out']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_visit', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['user_id']);

            
            $table->dropIndex(['brand_id', 'check_out']);
        });

        Schema::table('new_visit', function (Blueprint $table) {
            $table->dropColumn('brand_id');
            $table->dropColumn('user_id');
        });
    }
}
