<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('business_slug');
            $table->string('business_logo');
            $table->string('business_banner');
            $table->string('business_email')->unique();
            $table->string('business_phone')->unique();
            $table->string('business_address');
            $table->json('business_segment');
            $table->unsignedBigInteger('user_id');
            $table->boolean('business_status')->default(false);
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('business');
    }
}
