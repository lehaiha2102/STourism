<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booker');
            $table->unsignedBigInteger('room_id');
            $table->dateTime('checkin_time');
            $table->dateTime('checkout_time');
            $table->integer('quantity')->default(1);
            $table->boolean('booking_status')->default(false);
            $table->boolean('advance_payment_check')->default(false);
            $table->double('advance_payment')->default(0);
            $table->foreign('booker')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('booking');
    }
}