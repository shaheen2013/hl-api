<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddUserDataVirtualColumnsToNewUserBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_user_brand', function (Blueprint $table) {
            $table->string('user_email')->virtualAs('JSON_UNQUOTE(json_extract(`user_data`, \'$."email"\'))');
            $table->string('user_name')->storedAs('JSON_UNQUOTE(json_extract(`user_data`, \'$."name"\'))')->always();
            
            $table->index(['user_email', 'brand_id']);
        });

        Schema::table('new_user_brand', function (Blueprint $table) {
            DB::statement('ALTER TABLE new_user_brand ADD FULLTEXT user_brand_user_name_ftx (user_name)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_user_brand', function (Blueprint $table) {
            DB::statement('ALTER TABLE new_user_brand DROP INDEX user_brand_user_name_ftx');
            $table->dropIndex(['user_email', 'brand_id']);
            $table->dropColumn('user_name');
            $table->dropColumn('user_email');
        });
    }
}
