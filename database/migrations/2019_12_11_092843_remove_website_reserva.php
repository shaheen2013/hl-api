<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class RemoveWebsiteReserva extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // We call the seeder that transfers url data before deleting the column which contains it.
        Artisan::call('db:seed', [
            '--class' => 'MigrateOldLanguageWebsiteUrl',
            '--force' => true
        ]);

        Schema::table('hoteles', function(Blueprint $table) {
            $table->dropColumn(['websiteReserva', 'websiteReserva_es', 'websiteReserva_de', 'websiteReserva_fr']);
        });

        Schema::table('cadena', function(Blueprint $table) {
            $table->dropColumn(['websiteReserva', 'websiteReserva_es', 'websiteReserva_de', 'websiteReserva_fr']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hoteles', function(Blueprint $table) {
            $table->string('websiteReserva', 255);
            $table->string('websiteReserva_es', 255);
            $table->string('websiteReserva_de', 255);
            $table->string('websiteReserva_fr', 255);
        });

        Schema::table('cadena', function(Blueprint $table) {
            $table->string('websiteReserva', 255);
            $table->string('websiteReserva_es', 255);
            $table->string('websiteReserva_de', 255);
            $table->string('websiteReserva_fr', 255);
        });
    }
}