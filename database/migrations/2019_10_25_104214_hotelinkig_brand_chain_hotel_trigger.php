<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HotelinkigBrandChainHotelTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER chain_hotel_brand
            AFTER INSERT ON cadena_hotel FOR EACH ROW
            BEGIN
                INSERT IGNORE INTO
                    brands
                (uuid, chain_id, brand_type_id)
                SELECT
                    UUID(),
                    NEW.id_cadena,
                    (
                        SELECT
                            id
                        FROM
                            brand_types
                        WHERE
                            type = 'chain'
                    ) AS brand_type_id;
                        
                UPDATE
                    brands br1
                LEFT JOIN
                    brands br2
                ON
                    br2.chain_id = NEW.id_cadena
                SET
                    br1.parent_id = br2.id
                WHERE
                    br1.hotel_id = NEW.id_hotel;
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
        DB::unprepared("DROP TRIGGER IF EXISTS chain_hotel_brand;");
    }
}
