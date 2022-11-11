<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestHelper;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestHelper
{
  private $userTypes = [
    'id' => 'integer',
    'name' => 'string',
    'email' => 'string',
    'username' => 'string',
    'created_at' => 'string',
    'updated_at' => 'string',
  ];

  public function test_login()
  {
    $user = User::factory()->create([
      'username' => 'user',
      'password' => Hash::make('123')
    ]);

    $data = [
      'username' => 'user',
      'password' => '123',
    ];

    $response = $this->request([
      'method' => 'POST',
      'url' => '/login',
      'data' => $data]
    );

    $response->assertStatus(200)
      ->assertJson(fn (AssertableJson $json) =>
        $json->where('success', true)
          ->whereType('data.token', 'string')
          ->has('data.user', fn($json) =>
            $json->whereAllType($this->userTypes)
          )
      );
  }

  public function test_login_incorrect_credential()
  {
    $user = User::factory()->create([
      'username' => 'user',
      'password' => Hash::make('123')
    ]);

    $data = [
      'username' => 'user',
      'password' => '1234',
    ];

    $response = $this->request([
      'method' => 'POST',
      'url' => '/login',
      'data' => $data
    ]);

    $this->assertResponseError($response, 401, 'The provided credentials are incorrect.');
  }

  public function test_login_bad_request()
  {
    $user = User::factory()->create([
      'username' => 'user',
      'password' => Hash::make('123')
    ]);

    $response = $this->request([
      'method' => 'POST',
      'url' => '/login',
    ]);

    $this->assertResponseError($response, 400);
  }

  public function test_me()
  {
    $response = $this->authRequest([
      'method' => 'GET',
      'url' => '/me'
    ]);

    $response->assertStatus(200)
      ->assertJson(fn (AssertableJson $json) =>
        $json->where('success', true)
          ->has('data.user', fn($json) =>
            $json->whereAllType($this->userTypes)
          )
      );
  }

  public function test_me_unauthenticated()
  {
    $response = $this->request([
      'method' => 'GET',
      'url' => '/me'
    ]);

    $response->assertStatus(401);
  }

  public function test_logout()
  {
    $response = $this->authRequest([
      'method' => 'GET',
      'url' => '/logout'
    ]);

    $response->assertStatus(200);
  }
}
