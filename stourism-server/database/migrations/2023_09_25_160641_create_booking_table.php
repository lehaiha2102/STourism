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
            $table->date('checkin_time');
            $table->date('checkout_time');
            $table->integer('booking_status')->default(1);
            $table->boolean('payment_check')->default(false);
            $table->double('payment')->default(0);
            $table->string('booker_email')->nullable();
            $table->string('booker_phone')->nullable();
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
