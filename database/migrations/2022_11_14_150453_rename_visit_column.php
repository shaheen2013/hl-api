<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameVisitColumn extends Migration
{
    public function up()
    {
        Schema::table('new_visit', function (Blueprint $table) {
            $table->renameColumn('visit', 'reservation');
        });
    }


    public function down()
    {
        Schema::table('new_visit', function (Blueprint $table) {
            $table->renameColumn('reservation', 'visit');
        });
    }
}
