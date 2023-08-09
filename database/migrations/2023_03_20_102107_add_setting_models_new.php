<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSettingModelsNew extends Migration
{

    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
          /*  $table->string('OperationCash')->after('paymentDocument')->nullable();
            $table->string('OperationCard')->after('paymentDocument')->nullable();
            $table->string('OperationMobile')->after('paymentDocument')->nullable();*/
        });
    }


    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('OperationCash');
            $table->dropColumn('OperationCard');
            $table->dropColumn('OperationMobile');
        });
    }
}
