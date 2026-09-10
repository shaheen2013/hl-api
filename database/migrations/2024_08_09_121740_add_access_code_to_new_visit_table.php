<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccessCodeToNewVisitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_visit', function (Blueprint $table) {
            $table->string('access_code')->virtualAs('JSON_UNQUOTE(JSON_EXTRACT(reservation, CONCAT("$.connections[", JSON_LENGTH(reservation->"$.connections") - 1, "].access_code")))');
            $table->index(['access_code', 'brand_id']);
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
            $table->dropIndex(['access_code', 'brand_id']);
            $table->dropColumn('access_code');
        });
    }
}
