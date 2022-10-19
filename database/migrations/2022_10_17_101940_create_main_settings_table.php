<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainSettingsTable extends Migration
{

    public function up()
    {
        Schema::create('main_settings', function (Blueprint $table) {
            $table->string('accountId')->unique()->primary();
            $table->string('TokenMoySklad');
            $table->string('TokenKaspi');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('main_settings');
    }
}
