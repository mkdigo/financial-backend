<?php

namespace Tests;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Testing\TestResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\AssertableJson;

class TestHelper extends TestCase {
  private $headers = ['Accept' => 'application/json'];

  protected function request(array $params = [])
  {
    if(!isset($params['method'])) $params['method'] = 'get';
    if(!isset($params['url'])) $params['url'] = '';
    if(!isset($params['data'])) $params['data'] = [];

    $params['url'] = "/api" . $params['url'];

    return $this->withHeaders($this->headers)->json($params['method'], $params['url'], $params['data']);
  }

  protected function authRequest(array $params = [])
  {
    if(!isset($params['method'])) $params['method'] = 'get';
    if(!isset($params['url'])) $params['url'] = '';
    if(!isset($params['data'])) $params['data'] = [];
    if(!isset($params['user'])) $params['user'] = null;

    $params['url'] = "/api" . $params['url'];

    if($params['user']) Sanctum::actingAs($params['user']);
    else Sanctum::actingAs(User::factory()->create());

    return $this->withHeaders($this->headers)->json($params['method'], $params['url'], $params['data']);
  }

  protected function assertResponseError($response, $code, $message = null)
  {
    $response->assertStatus($code)
      ->assertJson(function (AssertableJson $json) use($message) {
        $json->where('success', false);
        if($message) $json->where('message', $message);
        $json->whereAllType([
          'success' => 'boolean',
          'message' => 'string',
          'fields' => 'array|null',
          'errors' => 'array|null',
        ]);
      });
  }
}
