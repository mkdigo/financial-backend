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

    $response = $this->authRequest([
      'method' => 'GET',
      'url' => '/users'
    ]);

    $response->assertStatus(200)
      ->assertJson(fn(AssertableJson $json) =>
        $json->where('success', true)
          ->has('users.0', fn($json) =>
            $json->whereAllType($this->types)
          )
      );
  }

  public function test_create()
  {
    $data = $this->data;
    $data['password'] = 'Testing123';
    $data['password_confirmation'] = 'Testing123';

    $response = $this->authRequest([
      'method' => 'POST',
      'url' => '/users',
      'data' => $data,
    ]);

    $response->assertStatus(201)
      ->assertJson(fn(AssertableJson $json) =>
        $json->where('success', true)
          ->has('user', fn($json) =>
            $json->whereAllType($this->types)
          )
      );
  }

  public function test_create_bad_request()
  {
    $data = $this->data;
    $data['password'] = "Testing123";

    $response = $this->authRequest([
      'method' => 'POST',
      'url' => '/users',
      'data' => $data,
    ]);

    $this->assertResponseError($response, 400);
  }

  public function test_update()
  {
    $this->seed(UserSeeder::class);

    $response = $this->authRequest([
      'method' => 'PUT',
      'url' => '/users/1',
      'data' => $this->data,
    ]);

    $response->assertStatus(200)
      ->assertJson(fn(AssertableJson $json) =>
        $json->where('success', true)
          ->has('user', fn($json) =>
            $json->whereAllType($this->types)
          )
      );
  }

  public function test_update_bad_request()
  {
    $this->seed(UserSeeder::class);

    $response = $this->authRequest([
      'method' => 'PUT',
      'url' => '/users/1',
    ]);

    $this->assertResponseError($response, 400);
  }

  public function test_update_user_not_found()
  {
    $response = $this->authRequest([
      'method' => 'PUT',
      'url' => '/users/1000',
    ]);

    $this->assertResponseError($response, 404, 'Not found.');
  }

  public function test_delete()
  {
    $this->seed(UserSeeder::class);

    $response = $this->authRequest([
      'method' => 'DELETE',
      'url' => '/users/1'
    ]);

    $response->assertStatus(200)
      ->assertExactJson(['success' => true]);
  }

  public function test_delete_user_not_found()
  {
    $response = $this->authRequest([
      'method' => 'DELETE',
      'url' => '/users/1000'
    ]);

    $this->assertResponseError($response, 404, 'Not found.');
  }

  public function test_change_password()
  {
    $this->seed(UserSeeder::class);

    $data = [
      'password' => 'Testing123',
      'password_confirmation' => 'Testing123',
    ];

    $response = $this->authRequest([
      'method' => 'PUT',
      'url' => '/users/1/changepassword',
      'data' => $data,
    ]);

    $response->assertStatus(200)
      ->assertExactJson(['success' => true]);
  }

  public function test_change_password_bad_request()
  {
    $this->seed(UserSeeder::class);

    $response = $this->authRequest([
      'method' => 'PUT',
      'url' => '/users/1/changepassword',
    ]);

    $this->assertResponseError($response, 400);
  }

  public function test_change_password_user_not_found()
  {
    $response = $this->authRequest([
      'method' => 'PUT',
      'url' => '/users/1000/changepassword',
    ]);

    $this->assertResponseError($response, 404, 'Not found.');
  }
}
