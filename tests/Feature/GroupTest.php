<?php

namespace Tests\Feature;

use Tests\TestHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GroupTest extends TestHelper
{
  private $types = [
    'id' => 'integer',
    'name' => 'string',
    'description' => 'string|null',
  ];

  public function test_list()
  {
    $response = $this->authRequest([
      'method' => 'GET',
      'url' => '/groups'
    ]);

    $response->assertStatus(200)
      ->assertJson(fn(AssertableJson $json) =>
        $json->where('success', true)
          ->has('data.groups.0', fn($json) =>
            $json->whereAllType($this->types)
          )
      );
  }
}
