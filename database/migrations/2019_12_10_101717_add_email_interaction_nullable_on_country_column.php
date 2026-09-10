<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailInteractionNullableOnCountryColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $platform = DB::getDoctrineSchemaManager()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('email_interaction', function (Blueprint $table) {
            $table->string('country')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $platform = DB::getDoctrineSchemaManager()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');
        
        Schema::table('email_interaction', function (Blueprint $table) {
            $table->string('country')->nullable(false)->change();
        });
    }
}
