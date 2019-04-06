<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConversationParticipantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversation_participant', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('conversation_id')->unsigned();
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
            $table->morphs('participant');
            $table->boolean('is_read')->default(false);
            $table->boolean('is_left')->default(false);
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
        Schema::dropIfExists('conversation_participant');
    }
}
