<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntriesTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('entries', function (Blueprint $table) {
      $table->id();
      $table->date('inclusion');
      $table->unsignedBigInteger('debit_id');
      $table->unsignedBigInteger('credit_id');
      $table->unsignedBigInteger('value');
      $table->longText('note')->nullable();
      $table->timestamps();

      $table->foreign('debit_id')->references('id')->on('accounts')->onDelete('cascade');
      $table->foreign('credit_id')->references('id')->on('accounts')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('entries');
  }
}
