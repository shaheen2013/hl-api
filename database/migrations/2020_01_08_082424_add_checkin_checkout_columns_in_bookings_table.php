<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCheckinCheckoutColumnsInBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->date('check_out')->nullable()->after('amount');
            $table->date('check_in')->nullable()->after('amount');
            $table->index(['hotel_id', 'check_in', 'check_out']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['hotel_id', 'check_in', 'check_out']);
            $table->dropColumn('check_out');
            $table->dropColumn('check_in');
        });
    }
}
