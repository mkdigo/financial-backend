<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubGroupsTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('subgroups', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('group_id');
      $table->string('name');
      $table->longText('description')->nullable();
      $table->timestamps();

      $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
    });

    DB::table('subgroups')->insert([
      [
        'group_id' => 1,
        'name' => 'current_assets',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
      [
        'group_id' => 1,
        'name' => 'long_term_assets',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
      [
        'group_id' => 1,
        'name' => 'property',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
      [
        'group_id' => 1,
        'name' => 'other_assets',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
      [
        'group_id' => 2,
        'name' => 'current_liabilities',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
      [
        'group_id' => 2,
        'name' => 'long_term_liabilities',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
      [
        'group_id' => 2,
        'name' => 'other_liabilities',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
      [
        'group_id' => 3,
        'name' => 'equity',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
      [
        'group_id' => 4,
        'name' => 'revenues',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
      [
        'group_id' => 4,
        'name' => 'expenses',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
      [
        'group_id' => 4,
        'name' => 'tax',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
    ]);
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('subgroups');
  }
}
