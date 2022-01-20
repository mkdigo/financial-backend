<?php

namespace Tests\Feature;

use Tests\TestHelper;
use Database\Seeders\ProviderSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProviderTest extends TestHelper
{
  private $types = [
    'id' => 'integer',
    'name' => 'string',
    'email' => 'string|null',
    'phone' => 'string|null',
    'cellphone' => 'string|null',
    'state' => 'string|null',
    'city' => 'string|null',
    'address' => 'string|null',
    'note' => 'string|null',
    'created_at' => 'string',
    'updated_at' => 'string',
  ];

  private $data = [
    'name' => 'Company Name',
    'email' => 'example@mail.com',
    'phone' => '0000-00-0000',
    'cellphone' => '000-0000-0000',
    'state' => 'State',
    'city' => 'City',
    'address' => 'Addres for this company',
    'note' => 'Some notes',
  ];

  public function test_get()
  {
    $this->seed(ProviderSeeder::class);

    $params = [];

    $response = $this->authRequest('GET', '/api/providers', $params);

    $response->assertStatus(200);

    [$expected] = $this->expected('provider', $this->types, $response->json()['providers'][0]);

    $response->assertJsonStructure([
      'success',
      'providers' => [
        '*' => $expected,
      ],
    ]);
  }

  public function test_get_bad_request()
  {
    $this->seed(ProviderSeeder::class);

    $params = ['search' => 1];

    $response = $this->authRequest('GET', '/api/providers', $params);

    $this->assertResponseError($response, 400);
  }

  public function test_store()
  {
    $response = $this->authRequest('POST', '/api/providers', $this->data);

    $response->assertStatus(201);

    [$expected, $whereAllType] = $this->expected('provider', $this->types, $response->json()['provider']);

    $response->assertJson(fn (AssertableJson $json) =>
      $json->whereType('success', 'boolean')
        ->whereAllType($whereAllType)
    );
  }

  public function test_store_bad_request()
  {
    $response = $this->authRequest('POST', '/api/providers', []);

    $this->assertResponseError($response, 400);
  }

  public function test_update()
  {
    $this->seed(ProviderSeeder::class);

    $response = $this->authRequest('PUT', '/api/providers/1', $this->data);

    $response->assertStatus(200);

    [$expected, $whereAllType] = $this->expected('provider', $this->types, $response->json()['provider']);

    $response->assertJson(fn (AssertableJson $json) =>
      $json->whereType('success', 'boolean')
        ->whereAllType($whereAllType)
    );
  }

  public function test_update_bad_request()
  {
    $this->seed(ProviderSeeder::class);

    $response = $this->authRequest('PUT', '/api/providers/1', []);

    $this->assertResponseError($response, 400);
  }

  public function test_update_not_found()
  {
    $this->seed(ProviderSeeder::class);

    $response = $this->authRequest('PUT', '/api/providers/10000', $this->data);

    $this->assertResponseError($response, 404, 'Provider not found.');
  }

  public function test_delete()
  {
    $this->seed(ProviderSeeder::class);

    $response = $this->authRequest('DELETE', '/api/providers/1');

    $response->assertStatus(200);
  }

  public function test_delete_not_found()
  {
    $this->seed(ProviderSeeder::class);

    $response = $this->authRequest('DELETE', '/api/providers/10000');

    $this->assertResponseError($response, 404, 'Provider not found.');
  }
}
