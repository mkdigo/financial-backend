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

    $response = $this->authRequest('GET', '/api/entries');

    $response->assertStatus(200);

    [$expected] = $this->expected('entry', $this->types, $response->json()['entries'][0]);

    $response->assertJsonStructure([
      'success',
      'entries' => [
        '*' => $expected,
      ],
    ]);
  }

  public function test_store()
  {
    $response = $this->authRequest('POST', '/api/entries', $this->data);

    $response->assertStatus(201);

    [$expected, $whereAllType] = $this->expected('entry', $this->types, $response->json()['entry']);

    $response->assertJson(fn (AssertableJson $json) =>
      $json->whereType('success', 'boolean')
        ->whereAllType($whereAllType)
    );
  }

  public function test_store_bad_request()
  {
    $response = $this->authRequest('POST', '/api/entries', []);

    $this->assertResponseError($response, 400);
  }

  public function test_update()
  {
    $this->seed(EntrySeeder::class);

    $response = $this->authRequest('PUT', '/api/entries/1', $this->data);

    $response->assertStatus(200);

    [$expected, $whereAllType] = $this->expected('entry', $this->types, $response->json()['entry']);

    $response->assertJson(fn (AssertableJson $json) =>
      $json->whereType('success', 'boolean')
        ->whereAllType($whereAllType)
    );
  }

  public function test_update_not_found()
  {
    $this->seed(EntrySeeder::class);

    $response = $this->authRequest('PUT', '/api/entries/10000', $this->data);

    $this->assertResponseError($response, 404, 'Entry not found.');
  }

  public function test_update_bad_request()
  {
    $this->seed(EntrySeeder::class);

    $response = $this->authRequest('PUT', '/api/entries/1', []);

    $this->assertResponseError($response, 400);
  }

  public function test_delete()
  {
    $this->seed(EntrySeeder::class);

    $response = $this->authRequest('DELETE', '/api/entries/1');

    $response->assertStatus(200);
  }

  public function test_delete_not_found()
  {
    $this->seed(EntrySeeder::class);

    $response = $this->authRequest('DELETE', '/api/entries/10000');

    $this->assertResponseError($response, 404, 'Entry not found.');
  }
}
