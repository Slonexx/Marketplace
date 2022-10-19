<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderSettingsTable extends Migration
{

    public function up()
    {
        Schema::create('order_settings', function (Blueprint $table) {
            $table->id();
            $table->string('accountId');
            $table->foreign('accountId')->references('accountId')->on('main_settings')->cascadeOnDelete();
            $table->string('Organization');
            $table->string('PaymentDocument');
            $table->string('Document');
            $table->string('PaymentAccount')->nullable();
            $table->string('CheckCreatProduct');
            $table->string('Store');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_settings');
    }
}
