<?php

namespace Tests\Feature;

use Tests\TestHelper;
use Database\Seeders\ProductSeeder;
use Database\Seeders\ProviderSeeder;
use Illuminate\Foundation\Testing\WithFaker;
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

    $response = $this->authRequest('GET', 'api/products');

    $response->assertStatus(200);

    [$expected] = $this->expected('products', $this->types, $response['products'][0]);

    $response->assertJsonStructure([
      'success',
      'products' => [
        '*' => $expected,
      ],
    ]);
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

    $response = $this->authRequest('GET', 'api/products', $data);

    $this->assertResponseError($response, 400, 'The barcode must be an integer. The price must be an integer.');
  }

  public function test_store()
  {
    $this->seed(ProviderSeeder::class);

    $response = $this->authRequest('POST', 'api/products', $this->data);

    $response->assertStatus(200);

    [$expected, $whereAllType] = $this->expected('product', $this->types, $response['product']);

    $response->assertJson(fn($json) =>
      $json->where('success', true)
        ->whereAllType($whereAllType)
    );
  }

  public function test_store_bad_request()
  {
    $response = $this->authRequest('POST', 'api/products', []);

    $this->assertResponseError($response, 400);
  }

  public function test_update()
  {
    $this->seed(ProductSeeder::class);

    $response = $this->authRequest('PUT', 'api/products/1', $this->data);

    $response->assertStatus(200);

    [$expected, $whereAllType] = $this->expected('product', $this->types, $response['product']);

    $response->assertJson(fn($json) =>
      $json->where('success', true)
        ->whereAllType($whereAllType)
    );
  }

  public function test_update_not_found()
  {
    $this->seed(ProductSeeder::class);

    $response = $this->authRequest('PUT', 'api/products/10000', $this->data);

    $this->assertResponseError($response, 404, 'Record not found.');
  }

  public function test_update_bad_request()
  {
    $this->seed(ProductSeeder::class);

    $response = $this->authRequest('PUT', 'api/products/1', []);

    $this->assertResponseError($response, 400);
  }

  public function test_delete()
  {
    $this->seed(ProductSeeder::class);

    $response = $this->authRequest('DELETE', 'api/products/1');

    $response->assertStatus(200);
  }

  public function test_delete_not_found()
  {
    $this->seed(ProductSeeder::class);

    $response = $this->authRequest('DELETE', 'api/products/10000');

    $this->assertResponseError($response, 404, 'Record not found.');
  }
}
