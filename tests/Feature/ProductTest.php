<?php

namespace Tests\Feature;

use Tests\TestHelper;
use Database\Seeders\ProductSeeder;
use Database\Seeders\ProviderSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestHelper
{
  private $types = [
    'id' => 'integer',
    'barcode' => 'integer|null',
    'ref' => 'string|null',
    'name' => 'string',
    'description' => 'string|null',
    'cost' => 'integer',
    'price' => 'integer',
    'quantity' => 'integer',
    'note' => 'string|null',
    'provider' => 'array',
  ];

  private $data = [
    'provider_id' => 1,
    'barcode' => '1234567890123',
    'ref' => '55555',
    'name' => 'Product Name',
    'description' => 'Product Description',
    'cost' => 1000,
    'price' => 2000,
    'quantity' => 20,
    'note' => 'Some note',
  ];

  public function test_list()
  {
    $this->seed(ProductSeeder::class);

    $response = $this->authRequest([
      'method' =>'GET',
      'url' =>'/products'
    ]);

    $response->assertStatus(200)
      ->assertJson(fn(AssertableJson $json) =>
        $json->where('success', true)
          ->has('products.0', fn($json) =>
            $json->whereAllType($this->types)
          )
      );
  }

  public function test_list_bad_request()
  {
    $this->seed(ProductSeeder::class);

    $data = [
      'search' => '',
      'barcode' => 'test',
      'ref' => '',
      'price' => 'test',
    ];

    $response = $this->authRequest([
      'method' => 'GET',
      'url' => '/products',
      'data' => $data,
    ]);

    $this->assertResponseError($response, 400, 'The barcode must be an integer. The price must be an integer.');
  }

  public function test_store()
  {
    $this->seed(ProviderSeeder::class);

    $response = $this->authRequest([
      'method' => 'POST',
      'url' => '/products',
      'data' => $this->data,
    ]);

    $response->assertStatus(201)
      ->assertJson(fn(AssertableJson $json) =>
        $json->where('success', true)
          ->has('product', fn($json) =>
            $json->whereAllType($this->types)
          )
      );
  }

  public function test_store_bad_request()
  {
    $response = $this->authRequest([
      'method' => 'POST',
      'url' => '/products',
    ]);

    $this->assertResponseError($response, 400);
  }

  public function test_update()
  {
    $this->seed(ProductSeeder::class);

    $response = $this->authRequest([
      'method' => 'PUT',
      'url' => '/products/1',
      'data' => $this->data
    ]);

    $response->assertStatus(200)
      ->assertJson(fn(AssertableJson $json) =>
        $json->where('success', true)
          ->has('product', fn($json) =>
            $json->whereAllType($this->types)
          )
      );
  }

  public function test_update_not_found()
  {
    $this->seed(ProductSeeder::class);

    $response = $this->authRequest([
      'method' => 'PUT',
      'url' => '/products/10000',
      'data' => $this->data,
    ]);

    $this->assertResponseError($response, 404, 'Not found.');
  }

  public function test_update_bad_request()
  {
    $this->seed(ProductSeeder::class);

    $response = $this->authRequest([
      'method' => 'PUT',
      'url' => '/products/1',
    ]);

    $this->assertResponseError($response, 400);
  }

  public function test_delete()
  {
    $this->seed(ProductSeeder::class);

    $response = $this->authRequest([
      'method' =>'DELETE',
      'url' =>'/products/1'
    ]);

    $response->assertStatus(200);
  }

  public function test_delete_not_found()
  {
    $this->seed(ProductSeeder::class);

    $response = $this->authRequest([
      'method' =>'DELETE',
      'url' =>'/products/10000'
    ]);

    $this->assertResponseError($response, 404, 'Not found.');
  }
}
