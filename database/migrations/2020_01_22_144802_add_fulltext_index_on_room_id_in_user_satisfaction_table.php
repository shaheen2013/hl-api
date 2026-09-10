<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFulltextIndexOnRoomIdInUserSatisfactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_satisfaction', function (Blueprint $table) {
            DB::statement('ALTER TABLE user_satisfaction DROP INDEX comment_ftidx');
            DB::statement('ALTER TABLE user_satisfaction ADD FULLTEXT user_satisfaction_comment_room_ft_index (id_room, comentario)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_satisfaction', function (Blueprint $table) {
            DB::statement('ALTER TABLE user_satisfaction DROP INDEX user_satisfaction_comment_room_ft_index');
            DB::statement('ALTER TABLE user_satisfaction ADD FULLTEXT comment_ftidx (comentario)');
        });
    }
}
