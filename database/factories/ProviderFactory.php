<?php

namespace Database\Factories;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProviderFactory extends Factory
{
  /**
  * The name of the factory's corresponding model.
  *
  * @var string
  */
  protected $model = Provider::class;

  /**
  * Define the model's default state.
  *
  * @return array
  */
  public function definition()
  {
    return [
      'name' => $this->faker->company(),
      'email' => $this->faker->email(),
      'phone' => $this->faker->phoneNumber(),
      'cellphone' => $this->faker->phoneNumber(),
      'zipcode' => $this->faker->randomNumber(3, true) . "-" . $this->faker->randomNumber(4, true),
      'state' => $this->faker->state(),
      'city' => $this->faker->city(),
      'address' => $this->faker->address(),
      'note' => $this->faker->sentence(),
    ];
  }
}
