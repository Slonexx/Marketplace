<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalsTable extends Migration
{

    public function up()
    {
        Schema::create('personals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string("appId");
            $table->string("accountId");
            $table->string("path");
        });
    }


    public function down()
    {
        Schema::dropIfExists('personals');
    }
}
