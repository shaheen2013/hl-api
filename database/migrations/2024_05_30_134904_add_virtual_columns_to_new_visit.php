<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVirtualColumnsToNewVisit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_visit', function (Blueprint $table) {
            $table->string('res_id')->virtualAs('JSON_UNQUOTE(json_extract(`reservation`, \'$."res_id"\'))');
            $table->string('pms_id')->virtualAs('JSON_UNQUOTE(json_extract(`reservation`, \'$."pms_id"\'))');
            
            // Composite indexes
            $table->index(['res_id','brand_id']);
            $table->index(['pms_id','brand_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_visit', function (Blueprint $table) {
           $table->dropIndex(['res_id','brand_id']);
           $table->dropIndex(['pms_id','brand_id']);
           $table->dropColumn('res_id');
           $table->dropColumn('pms_id');
        });
    }
}
