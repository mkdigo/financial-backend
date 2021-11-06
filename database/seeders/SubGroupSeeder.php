<?php

namespace Database\Seeders;

use App\Models\Subgroup;
use Illuminate\Database\Seeder;

class SubGroupSeeder extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    Subgroup::factory()->create(['name' => 'current_assets']);
    Subgroup::factory()->create(['name' => 'long_term_assets']);
    Subgroup::factory()->create(['name' => 'property']);
    Subgroup::factory()->create(['name' => 'other_assets']);
    Subgroup::factory()->create(['name' => 'current_liabilities']);
    Subgroup::factory()->create(['name' => 'long_term_liabilities']);
    Subgroup::factory()->create(['name' => 'other_liabilities']);
    Subgroup::factory()->create(['name' => 'equity']);
    Subgroup::factory()->create(['name' => 'revenues']);
    Subgroup::factory()->create(['name' => 'expenses']);
    Subgroup::factory()->create(['name' => 'tax']);
  }
}
