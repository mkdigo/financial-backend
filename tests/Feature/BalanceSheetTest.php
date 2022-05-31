<?php

namespace Tests\Feature;

use Tests\TestHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BalanceSheetTest extends TestHelper
{
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

    $response = $this->authRequest([
      'method' => 'GET',
      'url' => '/balance',
      'data' => $data
    ]);

    $response->assertStatus(200);

    $response->assertJson(fn (AssertableJson $json) =>
      $json->where('success', true)
        ->has('data', fn($json) => $json
          ->has('balance', fn($json) => $json
            ->has('assets', fn($json) => $json
              ->whereAllType($this->balanceAssetsType)
            )
            ->has('liabilities', fn($json) => $json
              ->whereAllType($this->balanceLiabilitiesType)
            )
            ->whereType('equity', 'array')
            ->has('amounts', fn($json) => $json
              ->whereAllType($this->balanceAmountsType)
            )
          )
          ->has('incomeStatement', fn($json) => $json
            ->whereAllType($this->incomeStatementType)
            ->has('amounts', fn($json) => $json
              ->whereAllType($this->incomeStatementAmountsType)
            )
          )
        )
    );
  }

  public function test_balance_sheet_bad_request()
  {
    $response = $this->authRequest([
      'method' => 'GET',
      'url' => '/balance'
    ]);

    $this->assertResponseError($response, 400);
  }
}
