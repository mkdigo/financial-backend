<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
  /**
  * The name of the factory's corresponding model.
  *
  * @var string
  */
  protected $model = Account::class;

  /**
  * Define the model's default state.
  *
  * @return array
  */
  public function definition()
  {
    return [
      'group_id' => $this->faker->numberBetween(1, 30),
      'subgroup_id' => $this->faker->numberBetween(1, 30),
      'name' => $this->faker->word(),
      'description' => $this->faker->sentence(),
    ];
  }
}
