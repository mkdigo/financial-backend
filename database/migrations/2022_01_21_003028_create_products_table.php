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
      $table->unsignedBigInteger('provider_id');
      $table->unsignedBigInteger('barcode')->unique()->nullable();
      $table->string('ref')->nullable();
      $table->string('name');
      $table->longText('description')->nullable();
      $table->unsignedInteger('cost');
      $table->unsignedInteger('price');
      $table->unsignedInteger('quantity')->default(0);
      $table->longText('note')->nullable();
      $table->timestamps();

      $table->foreign('provider_id')->references('id')->on('providers');
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
