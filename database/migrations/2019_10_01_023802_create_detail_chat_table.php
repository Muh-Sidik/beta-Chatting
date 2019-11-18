<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailChatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_chats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_detail_chat');
            $table->bigInteger('id_user_from')->unsigned();
            $table->bigInteger('id_user_to')->unsigned();
            $table->longText('chat');
            $table->tinyInteger('status')->default(0);
            $table->string('file')->nullable();
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
        Schema::dropIfExists('detail_chats');
    }
}
