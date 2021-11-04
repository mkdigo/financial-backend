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

    $response = $this->request('POST', '/api/login', $data);

    $response->assertStatus(200)
      ->assertJson(fn (AssertableJson $json) =>
        $json->whereAllType([
          'success' => 'boolean',
          'token' => 'string',
          'user' => 'array',
        ])
      );
  }

  public function test_me()
  {
    $response = $this->authRequest('GET', '/api/me');

    $response->assertStatus(200)
      ->assertJson(fn (AssertableJson $json) =>
        $json->whereAllType([
          'success' => 'boolean',
          'user' => 'array',
          'user.name' => 'string',
          'user.email' => 'string',
          'user.created_at' => 'string',
          'user.updated_at' => 'string',
        ])
      );
  }

  public function test_logout()
  {
    $response = $this->authRequest('GET', 'api/logout');

    $response->assertStatus(200);
  }
}
