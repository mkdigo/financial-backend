<?php

namespace Tests\Feature;

use App\Models\Entry;
use Tests\TestHelper;
use Database\Seeders\EntrySeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EntryTest extends TestHelper
{
  private $types = [
    "id" => 'integer',
    "inclusion" => "string",
    "debit_id" => 'integer',
    "debit_name" => "string",
    "credit_id" => 'integer',
    "credit_name" => "string",
    "value" => 'integer',
    "note" => "string|null",
    "created_at" => "string",
    "updated_at" => "string"
  ];

  private $data = [
    "inclusion" => "2021-11-01",
    "debit_id" => 1,
    "credit_id" => 2,
    "value" => 10000,
    "note" => "Test",
  ];

  public function test_list()
  {
    $this->seed(EntrySeeder::class);

    $data = [
      'search' => '',
      'start' => '2000-01-01',
      'end' => '2050-01-01',
    ];

    $response = $this->authRequest([
      'method' => 'GET',
      'url' => '/entries',
      'data' => $data
    ]);

    $response->assertStatus(200)
      ->assertJson(fn(AssertableJson $json) =>
        $json->where('success', true)
          ->has('entries.0', fn($json) =>
            $json->whereAllType($this->types)
          )
      );
  }

  public function test_list_bad_request()
  {
    $this->seed(EntrySeeder::class);

    $response = $this->authRequest([
      'method' => 'GET',
      'url' => '/entries'
    ]);

    $this->assertResponseError($response, 400);
  }

  public function test_store()
  {
    $response = $this->authRequest([
      'method' => 'POST',
      'url' => '/entries',
      'data' => $this->data
    ]);

    $response->assertStatus(201)
      ->assertJson(fn(AssertableJson $json) =>
        $json->where('success', true)
          ->has('entry', fn($json) =>
            $json->whereAllType($this->types)
          )
      );
  }

  public function test_store_bad_request()
  {
    $response = $this->authRequest([
      'method' => 'POST',
      'url' => '/entries',
    ]);

    $this->assertResponseError($response, 400);
  }

  public function test_update()
  {
    $this->seed(EntrySeeder::class);

    $response = $this->authRequest([
      'method' => 'PUT',
      'url' => '/entries/1',
      'data' => $this->data
    ]);

    $response->assertStatus(200)
      ->assertJson(fn(AssertableJson $json) =>
        $json->where('success', true)
          ->has('entry', fn($json) =>
            $json->whereAllType($this->types)
          )
      );
  }

  public function test_update_not_found()
  {
    $this->seed(EntrySeeder::class);

    $response = $this->authRequest([
      'method' => 'PUT',
      'url' => '/entries/10000',
      'data' => $this->data
    ]);

    $this->assertResponseError($response, 404, 'Not found.');
  }

  public function test_update_bad_request()
  {
    $this->seed(EntrySeeder::class);

    $response = $this->authRequest([
      'method' => 'PUT',
      'url' => '/entries/1'
    ]);

    $this->assertResponseError($response, 400);
  }

  public function test_delete()
  {
    $this->seed(EntrySeeder::class);

    $response = $this->authRequest([
      'method' => 'DELETE',
      'url' => '/entries/1'
    ]);

    $response->assertStatus(200);
  }

  public function test_delete_not_found()
  {
    $this->seed(EntrySeeder::class);

    $response = $this->authRequest([
      'method' => 'DELETE',
      'url' => '/entries/10000'
    ]);

    $this->assertResponseError($response, 404, 'Not found.');
  }

  public function test_list_expenses()
  {
    $this->seed(EntrySeeder::class);

    $data = [
      'search' => '',
      'start' => '2000-01-01',
      'end' => '2050-01-01',
    ];

    $response = $this->authRequest([
      'method' => 'GET',
      'url' => '/entries/expenses',
      'data' => $data
    ]);

    $response->assertStatus(200);

    $response->assertStatus(200)
      ->assertJson(fn(AssertableJson $json) =>
        $json->where('success', true)
          ->has('entries.0', fn($json) =>
            $json->whereAllType($this->types)
          )
      );
  }

  public function test_list_expenses_bad_request()
  {
    $this->seed(EntrySeeder::class);

    $response = $this->authRequest([
      'method' => 'GET',
      'url' => '/entries/expenses'
    ]);

    $this->assertResponseError($response, 400);
  }
}
