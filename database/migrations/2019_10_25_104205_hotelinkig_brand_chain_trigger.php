<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HotelinkigBrandChainTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER chain_brand
            AFTER INSERT ON cadena FOR EACH ROW
            BEGIN
                INSERT IGNORE INTO
                    brands
                (uuid, chain_id, brand_type_id)
                SELECT
                    UUID(),
                    NEW.id,
                    (
                        SELECT
                            id
                        FROM
                            brand_types
                        WHERE
                            type = 'chain'
                    ) AS brand_type_id;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP TRIGGER IF EXISTS chain_brand;");
    }
}
