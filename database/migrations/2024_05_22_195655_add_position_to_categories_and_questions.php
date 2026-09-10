<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPositionToCategoriesAndQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category', function (Blueprint $table) {
            $table->integer('position')->default(0);
        });

        Schema::table('question', function (Blueprint $table) {
            $table->integer('position')->default(0);
        });

        Artisan::call('db:seed', [
            '--class' => 'InitializePositionsSeeder',
            '--force' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category', function (Blueprint $table) {
            $table->dropColumn('position');
        });

        Schema::table('question', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
}
