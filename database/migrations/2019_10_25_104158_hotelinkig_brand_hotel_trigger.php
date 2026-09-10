<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HotelinkigBrandHotelTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER hotel_brand
            AFTER INSERT ON hoteles FOR EACH ROW
            BEGIN
                INSERT IGNORE INTO
                    brands
                (hotel_id, uuid, parent_id, brand_type_id)
                SELECT
                    h.id AS hotel_id,
                    UUID(),
                    c.id AS parent_id,
                    (
                        SELECT
                            id
                        FROM
                            brand_types
                        WHERE
                            type = 'hotel'
                    ) AS brand_type_id
                FROM
                    hoteles h
                LEFT JOIN
                    cadena_hotel ch
                ON
                    ch.id_hotel = h.id
                LEFT JOIN
                    brands c
                ON
                    c.chain_id = ch.id_cadena
                WHERE
                    h.id = NEW.id;
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
        DB::unprepared("DROP TRIGGER IF EXISTS hotel_brand;");
    }
}
