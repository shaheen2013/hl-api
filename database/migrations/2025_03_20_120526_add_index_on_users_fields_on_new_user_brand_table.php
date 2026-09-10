<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexOnUsersFieldsOnNewUserBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_user_brand', function (Blueprint $table) {
            $table->string('user_gender')->virtualAs('
                CASE 
                    WHEN JSON_EXTRACT(`user_data`, "$.gender") IS NULL THEN NULL 
                    WHEN JSON_UNQUOTE(JSON_EXTRACT(`user_data`, "$.gender")) = "null" THEN NULL
                    ELSE JSON_UNQUOTE(JSON_EXTRACT(`user_data`, "$.gender"))
                END
            ');
            $table->string('user_generation')->virtualAs('
                CASE 
                    WHEN JSON_EXTRACT(`user_data`, "$.generation") IS NULL THEN NULL 
                    WHEN JSON_UNQUOTE(JSON_EXTRACT(`user_data`, "$.generation")) = "null" THEN NULL
                    ELSE JSON_UNQUOTE(JSON_EXTRACT(`user_data`, "$.generation"))
                END
            ');
            $table->string('user_country')->virtualAs('JSON_UNQUOTE(json_extract(`user_data`, \'$."country"\'))');
            $table->string('user_birthdate')->virtualAs('JSON_UNQUOTE(json_extract(`user_data`, \'$."birthdate"\'))');
            $table->string('user_day_born')->virtualAs('
                CASE 
                    WHEN JSON_EXTRACT(user_data, "$.birthdate") IS NULL THEN NULL
                    WHEN JSON_UNQUOTE(JSON_EXTRACT(user_data, "$.birthdate")) = "null" THEN NULL
                    WHEN JSON_UNQUOTE(JSON_EXTRACT(user_data, "$.birthdate")) = "" THEN NULL
                    WHEN JSON_UNQUOTE(JSON_EXTRACT(user_data, "$.birthdate")) NOT REGEXP "^[0-9]{4}-[0-9]{2}-[0-9]{2}$" THEN NULL
                    ELSE DATE_FORMAT(JSON_UNQUOTE(JSON_EXTRACT(user_data, "$.birthdate")), "%m-%d")
                END
            ');
            $table->string('user_lang')->virtualAs('JSON_UNQUOTE(json_extract(`user_data`, \'$."lang"\'))');
            $table->string('user_phone_number')->virtualAs('
                CASE 
                    WHEN JSON_EXTRACT(`user_data`, "$.phone_number") IS NULL THEN NULL 
                    WHEN JSON_UNQUOTE(JSON_EXTRACT(`user_data`, "$.phone_number")) = "null" THEN NULL
                    ELSE JSON_UNQUOTE(JSON_EXTRACT(`user_data`, "$.phone_number"))
                END
            ');
            
            $table->index(['brand_id', 'user_gender']);
            $table->index(['brand_id', 'user_generation']);
            $table->index(['brand_id', 'user_country']);
            $table->index(['brand_id', 'user_birthdate']);
            $table->index(['brand_id', 'user_day_born']);
            $table->index(['brand_id', 'user_lang']);
            $table->index(['user_phone_number', 'brand_id']);
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
            $table->dropIndex(['user_phone_number', 'brand_id']);
            $table->dropIndex(['brand_id', 'user_lang']);
            $table->dropIndex(['brand_id', 'user_day_born']);
            $table->dropIndex(['brand_id', 'user_birthdate']);
            $table->dropIndex(['brand_id', 'user_country']);
            $table->dropIndex(['brand_id', 'user_generation']);
            $table->dropIndex(['brand_id', 'user_gender']);

            $table->dropColumn('user_phone_number');
            $table->dropColumn('user_lang');
            $table->dropColumn('user_day_born');
            $table->dropColumn('user_birthdate');
            $table->dropColumn('user_country');
            $table->dropColumn('user_generation');
            $table->dropColumn('user_gender');
        });
    }
}
