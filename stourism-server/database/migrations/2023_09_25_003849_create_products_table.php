<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('product_slug');
            $table->string('product_address');
            $table->string('product_phone')->unique();
            $table->string('product_email')->unique();
            $table->string('product_main_image');
            $table->json('product_image');
            $table->unsignedBigInteger('business_id');
            $table->text('product_description');
            $table->boolean('product_status')->default(true);
            $table->json('product_service');
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
        Schema::dropIfExists('products');
    }
}
