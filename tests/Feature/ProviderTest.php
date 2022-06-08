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
    'zipcode' => 'string|null',
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
    'zipcode' => '000-0000',
    'state' => 'State',
    'city' => 'City',
    'address' => 'Addres for this company',
    'note' => 'Some notes',
  ];

  public function test_get()
  {
    $this->seed(ProviderSeeder::class);

    $params = [];

    $response = $this->authRequest([
      'method' => 'GET',
      'url' => '/providers',
      'data' => $params
    ]);

    $response->assertStatus(200)
      ->assertJson(fn(AssertableJson $json) =>
        $json->where('success', true)
          ->has('providers.0', fn($json) =>
            $json->whereAllType($this->types)
          )
      );
  }

  public function test_get_bad_request()
  {
    $this->seed(ProviderSeeder::class);

    $params = ['search' => 1];

    $response = $this->authRequest([
      'method' => 'GET',
      'url' => '/providers',
      'data' => $params,
    ]);

    $this->assertResponseError($response, 400);
  }

  public function test_store()
  {
    $response = $this->authRequest([
      'method' => 'POST',
      'url' => '/providers',
      'data' => $this->data,
    ]);

    $response->assertStatus(201)
      ->assertJson(fn(AssertableJson $json) =>
        $json->where('success', true)
          ->has('provider', fn($json) =>
            $json->whereAllType($this->types)
          )
      );
  }

  public function test_store_bad_request()
  {
    $response = $this->authRequest([
      'method' => 'POST',
      'url' => '/providers',
    ]);

    $this->assertResponseError($response, 400);
  }

  public function test_update()
  {
    $this->seed(ProviderSeeder::class);

    $response = $this->authRequest([
      'method' => 'PUT',
      'url' => '/providers/1',
      'data' => $this->data,
    ]);

    $response->assertStatus(200)
      ->assertJson(fn(AssertableJson $json) =>
        $json->where('success', true)
          ->has('provider', fn($json) =>
            $json->whereAllType($this->types)
          )
      );
  }

  public function test_update_bad_request()
  {
    $this->seed(ProviderSeeder::class);

    $response = $this->authRequest([
      'method' => 'PUT',
      'url' => '/providers/1',
    ]);

    $this->assertResponseError($response, 400);
  }

  public function test_update_not_found()
  {
    $this->seed(ProviderSeeder::class);

    $response = $this->authRequest([
      'method' => 'PUT',
      'url' => '/providers/10000',
      'data' => $this->data,
    ]);

    $this->assertResponseError($response, 404, 'Not found.');
  }

  public function test_delete()
  {
    $this->seed(ProviderSeeder::class);

    $response = $this->authRequest([
      'method' => 'DELETE',
      'url' => '/providers/1'
    ]);

    $response->assertStatus(200);
  }

  public function test_delete_not_found()
  {
    $this->seed(ProviderSeeder::class);

    $response = $this->authRequest([
      'method' => 'DELETE',
      'url' => '/providers/10000'
    ]);

    $this->assertResponseError($response, 404, 'Not found.');
  }
}
