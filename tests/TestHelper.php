<?php

namespace Tests;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\AssertableJson;

class TestHelper extends TestCase {
  private $headers = ['Accept' => 'application/json'];

  protected function request($method, $url, $data = [])
  {
    return $this->withHeaders($this->headers)->json($method, $url, $data);
  }

  protected function authRequest($method, $url, $data = [])
  {
    Sanctum::actingAs(User::factory()->create());
    return $this->withHeaders($this->headers)->json($method, $url, $data);
  }

  protected function expected(string $name, array $types, array $array)
  {
    $expected = array_keys($types);
    $array = array_keys($array);

    $comparedArray_1 = array_diff($expected, $array);
    $comparedArray_2 = array_diff($array, $expected);

    $this->assertEquals(0, count($comparedArray_1));
    $this->assertEquals(0, count($comparedArray_2));

    $whereAllType = [];

    foreach($types as $key => $value) {
      $whereAllType[$name . '.' . $key] = $value;
    }

    return [$expected, $whereAllType];
  }

  protected function errorResponse($response, $code)
  {
    $response->assertStatus($code)
      ->assertJson(fn (AssertableJson $json) =>
        $json->whereAllType([
          'success' => 'boolean',
          'message' => 'string',
          'fields' => 'array|null',
          'errors' => 'array|null',
        ])
      );
  }
}
