<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestHelper;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestHelper
{
  private $types = [
    "id" => 'integer',
    "name" => "string",
    "username" => "string",
    "email" => "string",
    "created_at" => "string",
    "updated_at" => "string",
  ];

  private $data = [
    'name' => "User Test",
    'username' => "usertest",
    'email' => "test@mail.com",
    'password' => "Testing123",
    'password_confirmation' => "Testing123",
  ];

  public function test_create()
  {
    $response = $this->authRequest('POST', '/api/users', $this->data);

    $response->assertStatus(201);

    [$expectedUser, $whereAllType] = $this->expected('user', $this->types, $response->json()['user']);

    $response->assertJson(fn (AssertableJson $json) =>
      $json->whereType('success', 'boolean')
        ->whereAllType($whereAllType)
    );
  }
}
