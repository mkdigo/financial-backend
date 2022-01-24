<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
  /**
  * The name of the factory's corresponding model.
  *
  * @var string
  */
  protected $model = Product::class;

  /**
  * Define the model's default state.
  *
  * @return array
  */
  public function definition()
  {
    return [
      'provider_id' => 5,
      'barcode' => $this->faker->ean13(),
      'ref' => $this->faker->randomNumber(5, true),
      'name' => $this->faker->words(3, true),
      'description' => $this->faker->paragraph(),
      'cost' => $this->faker->numberBetween(1000, 3000),
      'price' => $this->faker->numberBetween(3500, 4500),
      'quantity' => $this->faker->randomNumber(1, true),
      'note' => $this->faker->sentence(),
    ];
  }
}
