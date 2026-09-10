<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeingsOnOfertaReferralToken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oferta_referral_token', function (Blueprint $table) {
            $table->integer('id_tipo_share')->nullable()->unsigned()->change();
            $table->integer('id_cupon')->nullable()->change();
            $table->integer('id_hotel')->nullable()->change();
        });

        Artisan::call('db:seed', array('--class' => 'CleanOfertaReferralTokenForeigns', '--force' => true));
        
        Schema::table('oferta_referral_token', function (Blueprint $table) {
            $table->foreign('id_tipo_share')->references('id')->on('tipos_share');
            $table->foreign('id_cupon')->references('id')->on('user_cupones');
            $table->foreign('id_hotel')->references('id')->on('hoteles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oferta_referral_token', function (Blueprint $table) {
            $table->dropForeign(['id_tipo_share']);
            $table->dropForeign(['id_cupon']);
            $table->dropForeign(['id_hotel']);
        });
    }
}
