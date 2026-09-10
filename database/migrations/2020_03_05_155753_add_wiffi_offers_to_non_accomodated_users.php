<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWiffiOffersToNonAccomodatedUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brand_offer_wifi', function (Blueprint $table) {
            $table->tinyInteger('accommodated')->after('is_default');
            $table->tinyInteger('non_accommodated')->after('accommodated');
        });

        Artisan::call('db:seed', [
            '--class' => 'SetWifiOffersAccommodatedFlags',
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
        Schema::table('brand_offer_wifi', function (Blueprint $table) {
            $table->dropColumn('accommodated');
            $table->dropColumn('non_accommodated');
        });
    }
}
