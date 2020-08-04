<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTextesPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_textes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('lang_id')->nullable();
            $table->string('umodel');
            $table->string('utype');
            $table->integer('uid');
            $table->string('data')->nullable();
            $table->timestamps();
        });

        Schema::create('ad_photos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('lang_id')->nullable();
            $table->string('umodel');
            $table->string('utype');
            $table->integer('uid');
            $table->string('data')->nullable();
            $table->integer('position')->nullable();
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
        Schema::drop('ad_textes');
        Schema::drop('ad_photos');
    }
}
