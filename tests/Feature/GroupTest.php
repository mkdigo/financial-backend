<?php

namespace Tests\Feature;

use Tests\TestHelper;
use Illuminate\Foundation\Testing\WithFaker;
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
    $response = $this->authRequest('GET', 'api/groups');

    $response->assertStatus(200);

    [$expected] = $this->expected('group', $this->types, $response->json()['groups'][0]);

    $response->assertJsonStructure([
      'success',
      'groups' => [
        '*' => $expected
      ],
    ]);
  }
}
