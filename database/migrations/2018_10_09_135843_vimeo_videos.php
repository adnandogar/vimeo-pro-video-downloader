<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VimeoVideos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vimeo_videos', function (Blueprint $table) {
            $table->increments('id');

            $table->string('client_id');
            $table->string('vimeo_id');
            $table->string('size');
            $table->string('name');
            $table->string('time_started');
            $table->string('time_ended');
            $table->string('elapsed_time');
            $table->string('fail_reason')->nullable();
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
