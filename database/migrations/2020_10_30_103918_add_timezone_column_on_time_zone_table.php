<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimezoneColumnOnTimeZoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('time_zone', function (Blueprint $table) {
            $table->string('time_zone', 50)->after('gmt');
            $table->dropUnique('description_uniq');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('time_zone', function (Blueprint $table) {
            $table->dropColumn('time_zone'); 
            $table->unique('description', 'description_uniq');	
        });
    }
}
