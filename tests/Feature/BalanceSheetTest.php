<?php

namespace Tests\Feature;

use Tests\TestHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BalanceSheetTest extends TestHelper
{
  private $balanceType = [
    'assets' => 'array',
    'liabilities' => 'array',
    'equity' => 'array',
    'amounts' => 'array',
  ];

  private $balanceAssetsType = [
    "current" => 'array',
    "longTerm" => 'array',
    "property" => 'array',
    "otherAssets" => 'array',
  ];

  private $balanceLiabilitiesType = [
    "current" => 'array',
    "longTerm" => 'array',
    "otherLiabilities" => 'array',
  ];

  private $balanceAmountsType = [
    "assets" => 'integer',
    "currentAssets" => 'integer',
    "longTermAssets" => 'integer',
    "property" => 'integer',
    "otherAssets" => 'integer',
    "currentLiabilities" => 'integer',
    "longTermLiabilities" => 'integer',
    "otherLiabilities" => 'integer',
    "equity" => 'integer',
    "liabilities" => 'integer',
  ];

  private $incomeStatementType = [
    "revenues" => 'array',
    "expenses" => 'array',
    "taxes" => 'array',
    "amounts" => 'array',
  ];

  private $incomeStatementAmountsType = [
    "revenues" => 'integer',
    "expenses" => 'integer',
    "taxes" => 'integer',
    "incomeBeforeTaxes" => 'integer',
    "incomeAfterTaxes" => 'integer',
  ];

  public function test_balance_sheet()
  {
    $data = [
      'year' => date('Y'),
      'month' => date('m'),
    ];

    $response = $this->authRequest('GET', '/api/balance', $data);

    $response->assertStatus(200);

    $this->expected('balance', $this->balanceType, $response->json()['data']['balance']);
    $this->expected('balance.assets', $this->balanceAssetsType, $response->json()['data']['balance']['assets']);
    $this->expected('balance.liabilities', $this->balanceLiabilitiesType, $response->json()['data']['balance']['liabilities']);
    $this->expected('balance.amounts', $this->balanceAmountsType, $response->json()['data']['balance']['amounts']);

    $this->expected('incomeStatement', $this->incomeStatementType, $response->json()['data']['incomeStatement']);
    $this->expected('incomeStatement.amounts', $this->incomeStatementAmountsType, $response->json()['data']['incomeStatement']['amounts']);

    $response->assertJson(fn (AssertableJson $json) =>
      $json->whereAllType([
        'success' => 'boolean',
        'data' => [
          'balance' => 'array',
          'incomeStatement' => 'array',
        ],
      ])
    );
  }

  public function test_balance_sheet_bad_request()
  {
    $response = $this->authRequest('GET', '/api/balance');

    $this->assertResponseError($response, 400);
  }
}
