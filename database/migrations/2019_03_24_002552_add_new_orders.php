<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('business_name')->nullable();
            $table->string('agency_latitude')->nullable();
            $table->string('agency_longtitude')->nullable();
            $table->string('agency_transaction_status')->default('NO');
            $table->string('agency_phone')->nullable();
            $table->string('user_transaction_status')->default('NO');
            $table->string('agency_id')->nullable();
            $table->string('agency_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('business_name');
            $table->dropColumn('agency_latitude');
            $table->dropColumn('agency_longtitude');
            $table->dropColumn('agency_transaction_status');
            $table->dropColumn('user_transaction_status');
            $table->dropColumn('agency_id');
            $table->dropColumn('agency_address');
            $table->dropColumn('agency_phone');


        });
    }
}
