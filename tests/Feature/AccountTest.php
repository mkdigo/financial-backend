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
    'description' => 'string|null',
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

    $response = $this->authRequest([
      'method' => 'GET',
      'url' => '/accounts',
      'data' => $data,
    ]);

    $response->assertStatus(200)
      ->assertJson(fn (AssertableJson $json) =>
        $json->where('success', true)
          ->has('data.accounts.0', fn($json) =>
            $json->whereAllType($this->types)
          )
      );
  }

  public function test_store()
  {
    $response = $this->authRequest([
      'method' => 'POST',
      'url' => '/accounts',
      'data' => $this->data
    ]);

    $response->assertStatus(201)
      ->assertJson(fn (AssertableJson $json) =>
        $json->where('success', true)
          ->has('data.account', fn($json) =>
            $json->whereAllType($this->types)
          )
      );
  }

  public function test_store_bad_request()
  {
    $response = $this->authRequest([
      'method' => 'POST',
      'url' => '/accounts',
    ]);

    $this->assertResponseError($response, 400);
  }

  public function test_check_if_cannot_create_two_identical_account()
  {
    Account::factory()->create(['name' => 'Test']);

    $response = $this->authRequest([
      'method' => 'POST',
      'url' => '/accounts',
      'data' => $this->data
    ]);

    $this->assertResponseError($response, 400, 'The name has already been taken.');
  }

  public function test_update()
  {
    $account = Account::factory()->create(['name' => 'Test']);

    $response = $this->authRequest([
      'method' => 'PUT',
      'url' => "/accounts/$account->id",
      'data' => $this->data
    ]);

    $response->assertStatus(200)
      ->assertJson(fn (AssertableJson $json) =>
        $json->where('success', true)
          ->has('data.account', fn($json) =>
            $json->whereAllType($this->types)
          )
      );
  }

  public function test_update_not_found()
  {
    $account = Account::factory()->create(['name' => 'Test']);

    $response = $this->authRequest([
      'method' => 'PUT',
      'url' => '/accounts/1000',
      'data' => $this->data
    ]);

    $this->assertResponseError($response, 404, 'Not found.');
  }

  public function test_update_bad_request()
  {
    $account = Account::factory()->create(['name' => 'Test']);

    $response = $this->authRequest([
      'method' => 'PUT',
      'url' => "/accounts/$account->id",
    ]);

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

    $response = $this->authRequest([
      'method' => 'PUT',
      'url' => "/accounts/$account_1->id",
      'data' => $data
    ]);

    $this->assertResponseError($response, 400, 'The name has already been taken.');
  }

  public function test_delete()
  {
    $response = $this->authRequest([
      'method' => 'DELETE',
      'url' => '/accounts/1'
    ]);

    $response->assertStatus(200);
  }

  public function test_delete_not_found()
  {
    $response = $this->authRequest([
      'method' => 'DELETE',
      'url' => '/accounts/1000'
    ]);

    $this->assertResponseError($response, 404, 'Not found.');
  }
}
