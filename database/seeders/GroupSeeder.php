<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    Group::factory()->create(['name' => 'assets']);
    Group::factory()->create(['name' => 'liabilities']);
    Group::factory()->create(['name' => 'equity']);
    Group::factory()->create(['name' => 'income_statement']);
  }
}
