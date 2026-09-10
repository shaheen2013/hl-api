<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBrandBackgroundColor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->char('background_color', 7)
                ->after('brand_type_id')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('background_color');
        });
    }
}
