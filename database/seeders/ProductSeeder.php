<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Provider;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    $provider = Provider::factory()->create();

    Product::factory()->for($provider)->count(50)->create();
  }
}
