<?php

namespace Database\Factories;

use App\Models\Entry;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntryFactory extends Factory
{
  /**
  * The name of the factory's corresponding model.
  *
  * @var string
  */
  protected $model = Entry::class;

  /**
  * Define the model's default state.
  *
  * @return array
  */
  public function definition()
  {
    return [
      'inclusion' => date('Y-m-d'),
      'debit_id' => $this->faker->numberBetween(1, 10),
      'credit_id' => $this->faker->numberBetween(1, 10),
      'value' => $this->faker->numberBetween(1000, 100000),
      'note' => $this->faker->sentence(),
    ];
  }
}
