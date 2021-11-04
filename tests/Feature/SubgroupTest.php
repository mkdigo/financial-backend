<?php

namespace Tests\Feature;

use Tests\TestHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubgroupTest extends TestHelper
{
  private $types = [
    'id' => 'integer',
    'name' => 'string',
    'description' => 'string',
    'group' => 'array',
  ];

  public function test_list()
  {
    $response = $this->authRequest('GET', 'api/subgroups');

    $response->assertStatus(200);

    [$expected] = $this->expected('subgroup', $this->types, $response->json()['subgroups'][0]);

    $response->assertJsonStructure([
      'success',
      'subgroups' => [
        '*' => $expected,
      ],
    ]);
  }
}
