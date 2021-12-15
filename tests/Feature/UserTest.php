<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestHelper;
use Laravel\Sanctum\Sanctum;
use Database\Seeders\UserSeeder;
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
  ];

  public function test_get()
  {
    $this->seed(UserSeeder::class);

    $response = $this->authRequest('GET', '/api/users');

    $response->assertStatus(200);

    [$expectedUser, $whereAllType] = $this->expected('user', $this->types, $response->json()['users'][0]);

    $response->assertJsonStructure([
      'success',
      'users' => [
        '*' => $expectedUser,
      ],
    ]);
  }

  public function test_create()
  {
    $data = $this->data;
    $data['password'] = 'Testing123';
    $data['password_confirmation'] = 'Testing123';

    $response = $this->authRequest('POST', '/api/users', $data);

    $response->assertStatus(201);

    [$expectedUser, $whereAllType] = $this->expected('user', $this->types, $response->json()['user']);

    $response->assertJson(fn (AssertableJson $json) =>
      $json->whereType('success', 'boolean')
        ->whereAllType($whereAllType)
    );
  }

  public function test_create_bad_request()
  {
    $data = $this->data;
    $data['password'] = "Testing123";

    $response = $this->authRequest('POST', '/api/users', $data);

    $this->errorResponse($response, 400);
  }

  public function test_update()
  {
    $this->seed(UserSeeder::class);

    $response = $this->authRequest('PUT', '/api/users/1', $this->data);

    $response->assertStatus(200);

    [$expectedUser, $whereAllType] = $this->expected('user', $this->types, $response->json()['user']);

    $response->assertJson(fn (AssertableJson $json) =>
      $json->whereType('success', 'boolean')
        ->whereAllType($whereAllType)
    );
  }

  public function test_update_bad_request()
  {
    $this->seed(UserSeeder::class);

    $response = $this->authRequest('PUT', '/api/users/1', []);

    $this->errorResponse($response, 400);
  }

  public function test_update_user_not_found()
  {
    $response = $this->authRequest('PUT', '/api/users/1000', []);

    $this->errorResponse($response, 404);
  }

  public function test_delete()
  {
    $this->seed(UserSeeder::class);

    $response = $this->authRequest('DELETE', '/api/users/1');

    $response->assertStatus(200)
      ->assertExactJson(['success' => true]);
  }

  public function test_delete_user_not_found()
  {
    $response = $this->authRequest('DELETE', '/api/users/1000');

    $this->errorResponse($response, 404);
  }

  public function test_change_password()
  {
    $this->seed(UserSeeder::class);

    $data = [
      'password' => 'Testing123',
      'password_confirmation' => 'Testing123',
    ];

    $response = $this->authRequest('PUT', '/api/users/1/changepassword', $data);

    $response->assertStatus(200)
      ->assertExactJson(['success' => true]);
  }

  public function test_change_password_bad_request()
  {
    $this->seed(UserSeeder::class);

    $response = $this->authRequest('PUT', '/api/users/1/changepassword', []);

    $this->errorResponse($response, 400);
  }

  public function test_change_password_user_not_found()
  {
    $response = $this->authRequest('PUT', '/api/users/1000/changepassword', []);

    $this->errorResponse($response, 404);
  }
}
