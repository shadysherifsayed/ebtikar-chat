<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageRecipientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_recipient', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('message_id')->unsigned();
            $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');
            $table->morphs('recipient');
            $table->boolean('is_sender')->default(false);
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_recipient');
    }
}
