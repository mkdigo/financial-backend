<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('username');
      $table->string('email')->unique();
      $table->timestamp('email_verified_at')->nullable();
      $table->string('password');
      $table->boolean('is_active')->default(1);
      $table->rememberToken();
      $table->timestamps();
    });

    DB::table('users')->insert([
      'name' => 'Rodrigo Mukudai',
      'username' => 'mkdigo',
      'email' => 'mkdigo@gmail.com',
      'password' => Hash::make('123'),
      'created_at' => Carbon::now()->toDateTimeString(),
      'updated_at' => Carbon::now()->toDateTimeString(),
    ]);
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('users');
  }
}
