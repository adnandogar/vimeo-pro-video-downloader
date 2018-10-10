<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VideosFeed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('videos_feed', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id');
            $table->string('video_id');
            $table->string('video_main_url');
            $table->string('size');
            $table->string('name');
            $table->integer('no_of_times')->default('0');
            $table->string('status');
            $table->rememberToken();
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
        //
    }
}
