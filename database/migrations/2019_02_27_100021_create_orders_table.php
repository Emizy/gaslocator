<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->string('order_id');
            $table->string('qty');
            $table->string('price');
            $table->string('user_name');
            $table->string('phone');
            $table->string('email');
            $table->string('address');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('gas_id');
            $table->string('order_status')->default('UNCOMPLETED');
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
        Schema::dropIfExists('orders');
    }
}
