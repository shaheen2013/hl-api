<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveIdTarjetaColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('id_tarjeta');
            $table->dropColumn('id_tarjeta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('id_tarjeta', 10)->after('id');
        });

        DB::statement('ALTER TABLE users ADD FULLTEXT id_tarjeta (id_tarjeta)');
    }
}
