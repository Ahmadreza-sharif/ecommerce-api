<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->id();
            $table->string('authority');
            $table->bigInteger('order_id')->unsigned();
            $table->bigInteger('coupen_id')->unsigned()->nullable();
            $table->tinyInteger('payment_method')->default(1);
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment');
    }
};
