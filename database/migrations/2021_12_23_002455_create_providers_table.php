<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvidersTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('providers', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('email')->nullable();
      $table->string('phone')->nullable();
      $table->string('cellphone')->nullable();
      $table->string('zipcode')->nullable();
      $table->string('state')->nullable();
      $table->string('city')->nullable();
      $table->string('address')->nullable();
      $table->longText('note')->nullable();
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
    Schema::dropIfExists('providers');
  }
}
