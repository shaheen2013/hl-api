<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewInteractionEmailTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('template');
            $table->enum('type', ['client', 'staff']);

            $table->index(['type']);
            $table->index(['template']);
        });

        Schema::create('email', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('brand_id')->unsigned();
            $table->string('mandrill_email_id')->nullable();
            $table->string('sender');
            $table->string('receiver');
            $table->integer('email_type_id')->unsigned();
            $table->timestamp('created_at'); 

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('email_type_id')->references('id')->on('email_type');

            $table->index(['brand_id', 'created_at']);
            $table->index(['brand_id', 'email_type_id', 'created_at']);
            $table->index(['mandrill_email_id']);
            $table->index(['created_at']);
        });

        Schema::create('email_interaction', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('email_id')->unsigned();
            $table->enum('interaction', ['open', 'click']);
            $table->string('country');
            $table->boolean('opened_in_mobile');

            $table->timestamp('created_at'); 

            $table->foreign('email_id')->references('id')->on('email')->onDelete('cascade');

            $table->unique(['email_id', 'interaction']);
            $table->index(['email_id', 'interaction', 'created_at']);
            $table->index(['interaction', 'created_at']);
        });
        
        Schema::create('mandrill_event', function (Blueprint $table) {
            $table->longText('event');
            $table->timestamp('created_at'); 

            $table->index(['created_at']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mandrill_event');
        Schema::dropIfExists('email_interaction');
        Schema::dropIfExists('email');
        Schema::dropIfExists('email_type');
    }
}
