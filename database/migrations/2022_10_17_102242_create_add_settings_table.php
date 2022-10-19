<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddSettingsTable extends Migration
{

    public function up()
    {
        Schema::create('add_settings', function (Blueprint $table) {
            $table->id();
            $table->string('accountId');
            $table->foreign('accountId')->references('accountId')->on('main_settings')->cascadeOnDelete();
            $table->string('Project')->nullable();
            $table->string('Saleschannel')->nullable();
            $table->string('APPROVED_BY_BANK')->nullable();
            $table->string('ACCEPTED_BY_MERCHANT')->nullable();
            $table->string('COMPLETED')->nullable();
            $table->string('CANCELLED')->nullable();
            $table->string('RETURNED')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('add_settings');
    }
}
