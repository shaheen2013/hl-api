<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ModifySatisfactionAnswersTableCollation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customized_satisfaction_answers', function (Blueprint $table) {
            DB::statement('ALTER TABLE customized_satisfaction_answers CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customized_satisfaction_answers', function (Blueprint $table) {
            DB::statement('ALTER TABLE customized_satisfaction_answers CONVERT TO CHARACTER SET latin1 COLLATE latin1_swedish_ci;');
        });
    }
}
