<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullableOnCurrentTimestampOnEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email', function (Blueprint $table) {
            $table->dropColumn('created_at');
        });
        
        Schema::table('email_interaction', function (Blueprint $table) {
            $table->dropColumn('created_at');
        });

        Schema::table('email', function (Blueprint $table) {
            $table->timestamps();
        });
        
        Schema::table('email_interaction', function (Blueprint $table) {
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
        
        Schema::table('email_interaction', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
        
        Schema::table('email', function (Blueprint $table) {
            $table->timestamp('created_at'); 
        });
        
        Schema::table('email_interaction', function (Blueprint $table) {
            $table->timestamp('created_at'); 
        });
    }
}
