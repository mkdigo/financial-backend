<?php

namespace Tests\Feature;

use Tests\TestHelper;
use App\Models\Account;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountTest extends TestHelper
{
  private $types = [
    'id' => 'integer',
    'group_id' => 'integer',
    'subgroup_id' => 'integer',
    'name' => 'string',
    'description' => 'string',
    'created_at' => 'string',
    'updated_at' => 'string',
    'group' => 'array',
    'subgroup' => 'array',
  ];

  private $data = [
    'group_id' => 1,
    'subgroup_id' => 1,
    'name' => 'Test',
    'description' => 'Test',
  ];

  public function test_list()
  {
    $data = [
      'group_id' => '1',
      'subgroup_id' => '1',
      'search' => '',
    ];

    $response = $this->authRequest('GET', '/api/accounts', $data);

    $response->assertStatus(200);

    [$expected] = $this->expected('account', $this->types, $response->json()['accounts'][0]);

    $response->assertJsonStructure([
      'success',
      'accounts' => [
        '*' => $expected,
      ],
    ]);
  }

  public function test_store()
  {
    $response = $this->authRequest('POST', '/api/accounts', $this->data);

    $response->assertStatus(201);

    [$expected, $whereAllType] = $this->expected('account', $this->types, $response->json()['account']);

    $response->assertJson(fn(AssertableJson $json) =>
      $json->whereType('success', 'boolean')
        ->whereAllType($whereAllType)
    );
  }

  public function test_store_bad_request()
  {
    $response = $this->authRequest('POST', '/api/accounts', []);

    $this->assertResponseError($response, 400);
  }

  public function test_check_if_cannot_create_two_identical_account()
  {
    Account::factory()->create(['name' => 'Test']);

    $response = $this->authRequest('POST', '/api/accounts', $this->data);

    $this->assertResponseError($response, 400);
  }

  public function test_update()
  {
    $account = Account::factory()->create(['name' => 'Test']);

    $response = $this->authRequest('PUT', '/api/accounts/' . $account->id, $this->data);

    $response->assertStatus(200);

    [$expected, $whereAllType] = $this->expected('account', $this->types, $response->json()['account']);

    $response->assertJson(fn(AssertableJson $json) =>
      $json->whereType('success', 'boolean')
        ->whereAllType($whereAllType)
    );
  }

  public function test_update_not_found()
  {
    $account = Account::factory()->create(['name' => 'Test']);

    $response = $this->authRequest('PUT', '/api/accounts/1000', $this->data);

    $this->assertResponseError($response, 404);
  }

  public function test_update_bad_request()
  {
    $account = Account::factory()->create(['name' => 'Test']);

    $response = $this->authRequest('PUT', '/api/accounts/' . $account->id, []);

    $this->assertResponseError($response, 400);
  }

  public function test_check_if_cannot_update_and_make_two_identical_account()
  {
    $account_1 = Account::factory()->create(['name' => 'Test1']);
    $account_2 = Account::factory()->create(['name' => 'Test2']);

    $data = [
      'group_id' => 1,
      'subgroup_id' => 1,
      'name' => 'Test2',
      'description' => 'Test',
    ];

    $response = $this->authRequest('PUT', '/api/accounts/' . $account_1->id, $data);

    $this->assertResponseError($response, 400);
  }

  public function test_delete()
  {
    $response = $this->authRequest('DELETE', '/api/accounts/1');

    $response->assertStatus(200);
  }

  public function test_delete_not_found()
  {
    $response = $this->authRequest('DELETE', '/api/accounts/1000');

    $this->assertResponseError($response, 404);
  }
}
