<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('accounts', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('group_id');
      $table->unsignedBigInteger('subgroup_id');
      $table->string('name')->unique();
      $table->longText('description')->nullable();
      $table->timestamps();

      $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
      $table->foreign('subgroup_id')->references('id')->on('subgroups')->onDelete('cascade');
    });

    DB::table('accounts')->insert([
      [
        'group_id' => 1,
        'subgroup_id' => 1,
        'name' => 'Caixa',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
      [
        'group_id' => 1,
        'subgroup_id' => 1,
        'name' => 'Banco',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
      [
        'group_id' => 1,
        'subgroup_id' => 1,
        'name' => 'Contas a receber',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
      [
        'group_id' => 2,
        'subgroup_id' => 5,
        'name' => 'Contas a pagar',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
      [
        'group_id' => 3,
        'subgroup_id' => 8,
        'name' => 'Capital Social',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
      [
        'group_id' => 3,
        'subgroup_id' => 8,
        'name' => 'Lucros Acumulados',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
      [
        'group_id' => 4,
        'subgroup_id' => 9,
        'name' => 'Receitas',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
      [
        'group_id' => 4,
        'subgroup_id' => 10,
        'name' => 'Aluguel',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
      [
        'group_id' => 4,
        'subgroup_id' => 10,
        'name' => 'Agua',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
      [
        'group_id' => 4,
        'subgroup_id' => 10,
        'name' => 'Energia',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
      [
        'group_id' => 4,
        'subgroup_id' => 10,
        'name' => 'Gás',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
      [
        'group_id' => 4,
        'subgroup_id' => 10,
        'name' => 'Seguro Automóveis',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
      [
        'group_id' => 4,
        'subgroup_id' => 10,
        'name' => 'Seguro Saúde',
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
      ],
      [
        'group_id' => 4,
        'subgroup_id' => 11,
        'name' => 'Impostos',
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
    Schema::dropIfExists('accounts');
  }
}
