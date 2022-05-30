<?php

namespace App\Repositories;

use App\Models\Entry;
use App\Helpers\Helper;
use App\Models\Account;
use App\Exceptions\ExceptionHandler;
use App\Repositories\BalanceSheetRepositoryInterface;

class BalanceSheetRepository implements BalanceSheetRepositoryInterface
{
  public function get()
  {
    $params = request()->only('year', 'month');

    $rules = [
      "year" => "required|string|digits:4",
      "month" => "required|string|digits:2"
    ];

    Helper::validator($params, $rules);

    $date = date("Y-m-t", strtotime($params['year'] . "-" . $params['month'] . "-01"));

    /*
      Groups
        1 - Assets
        2 - Liabilities
        3 - Owner's Equity
        4 - Income Statement
      Subgroups
        1 - Current Assets
        2 - Long Term Assets
        3 - Property
        4 - Other Assets
        5 - Current Liabilities
        6 - Long Term Liabilities
        7 - Other Liabilities
        8 - Owner's Equity
        9 - Revenues
        10 - Expenses
        11 - Tax
    */
    $incomeStatement = [];
    $balance = [];

    // Income Statement
    $incomeStatementAccounts = Account::where('group_id', 4)->orderBy('name')->get();

    $incomeStatement['revenues'] = [];
    $incomeStatement['expenses'] = [];
    $incomeStatement['taxes'] = [];
    $incomeStatement['amounts']['revenues'] = 0;
    $incomeStatement['amounts']['expenses'] = 0;
    $incomeStatement['amounts']['taxes'] = 0;
    $netIncome = 0;

    $balance['assets']['current'] = [];
    $balance['assets']['longTerm'] = [];
    $balance['assets']['property'] = [];
    $balance['assets']['otherAssets'] = [];
    $balance['liabilities']['current'] = [];
    $balance['liabilities']['longTerm'] = [];
    $balance['liabilities']['otherLiabilities'] = [];
    $balance['equity'] = [];

    $balance['amounts']['assets'] = 0;
    $balance['amounts']['currentAssets'] = 0;
    $balance['amounts']['longTermAssets'] = 0;
    $balance['amounts']['property'] = 0;
    $balance['amounts']['otherAssets'] = 0;

    $balance['amounts']['liabilities'] = 0;
    $balance['amounts']['currentLiabilities'] = 0;
    $balance['amounts']['longTermLiabilities'] = 0;
    $balance['amounts']['otherLiabilities'] = 0;
    $balance['amounts']['equity'] = 0;

    foreach($incomeStatementAccounts as $account) {
      $debits = Entry::where('debit_id', $account->id)->whereDate('inclusion', '<=', $date)->get();
      $credits = Entry::where('credit_id', $account->id)->whereDate('inclusion', '<=', $date)->get();

      $amount = $credits->sum('value') - $debits->sum('value');

      $netIncome += $amount;
    }

    foreach($incomeStatementAccounts as $account) {
      $debits = Entry::where('debit_id', $account->id)->whereYear('inclusion', $params['year'])->whereMonth('inclusion', $params['month'])->get();
      $credits = Entry::where('credit_id', $account->id)->whereYear('inclusion', $params['year'])->whereMonth('inclusion', $params['month'])->get();

      if($account->subgroup_id === 9) {
        $amount = $credits->sum('value') - $debits->sum('value');
        if($amount !== 0) {
          $incomeStatement['revenues'][$account->name] = $amount;
          $incomeStatement['amounts']['revenues'] +=  $amount;
        }
      } else if($account->subgroup_id === 11) {
        $amount = $debits->sum('value') - $credits->sum('value');
        if($amount !== 0) {
          $incomeStatement['taxes'][$account->name] = $amount;
          $incomeStatement['amounts']['taxes'] +=  $amount;
        }
      } else {
        $amount = $debits->sum('value') - $credits->sum('value');
        if($amount !== 0) {
          $incomeStatement['expenses'][$account->name] = $amount;
          $incomeStatement['amounts']['expenses'] +=  $amount;
        }
      }
    }

    $incomeStatement['amounts']['incomeBeforeTaxes'] = $incomeStatement['amounts']['revenues'] - $incomeStatement['amounts']['expenses'];
    $incomeStatement['amounts']['incomeAfterTaxes'] = $incomeStatement['amounts']['incomeBeforeTaxes'] - $incomeStatement['amounts']['taxes'];

    // Balance

    // Assets
    $assetsAccounts = Account::where('group_id', 1)->orderBy('name')->get();

    $currentAssets = 0;
    $longTermAssets = 0;
    $property = 0;
    $otherAssets = 0;

    foreach($assetsAccounts as $account) {
      $debits = Entry::where('debit_id', $account->id)->whereDate('inclusion', '<=', $date)->get();
      $credits = Entry::where('credit_id', $account->id)->whereDate('inclusion', '<=', $date)->get();
      $amount = $debits->sum('value') - $credits->sum('value');

      if($account->subgroup_id === 1) {
        // Current Assets
        $balance['assets']['current'][$account->name] = $amount;
        $currentAssets += $amount;
      } else if($account->subgroup_id === 2) {
        // Long Term Assets
        $balance['assets']['longTerm'][$account->name] = $amount;
        $longTermAssets += $amount;
      } else if($account->subgroup_id === 3) {
        // Property
        $balance['assets']['property'][$account->name] = $amount;
        $property += $amount;
      } else {
        // Other Assets
        $balance['assets']['otherAssets'][$account->name] = $amount;
        $otherAssets += $amount;
      }
    }
    $balance['amounts']['assets'] = $currentAssets + $longTermAssets + $property;
    $balance['amounts']['currentAssets'] = $currentAssets;
    $balance['amounts']['longTermAssets'] = $longTermAssets;
    $balance['amounts']['property'] = $property;
    $balance['amounts']['otherAssets'] = $otherAssets;

    // Liabilities
    $liabilitiesAccounts = Account::where('group_id', 2)->orderBy('name')->get();

    $currentLiabilities = 0;
    $longTermLiabilities = 0;
    $otherLiabilities = 0;

    foreach($liabilitiesAccounts as $account) {
      $debits = Entry::where('debit_id', $account->id)->whereDate('inclusion', '<=', $date)->get();
      $credits = Entry::where('credit_id', $account->id)->whereDate('inclusion', '<=', $date)->get();
      $amount = $credits->sum('value') - $debits->sum('value');

      if($account->subgroup_id === 5) {
        // Current Liabilities
        $balance['liabilities']['current'][$account->name] = $amount;
        $currentLiabilities += $amount;
      } else if($account->subgroup_id === 6) {
        // Long Term Liabilities
        $balance['liabilities']['longTerm'][$account->name] = $amount;
        $longTermLiabilities += $amount;
      } else {
        // Other Liabilities
        $balance['liabilities']['otherLiabilities'][$account->name] = $amount;
        $otherLiabilities += $amount;
      }
    }
    $balance['amounts']['currentLiabilities'] = $currentLiabilities;
    $balance['amounts']['longTermLiabilities'] = $longTermLiabilities;
    $balance['amounts']['otherLiabilities'] = $otherLiabilities;

    // Owner's Equity
    $equityAccounts = Account::where('group_id', 3)->orderBy('name')->get();

    $equity = 0;

    foreach($equityAccounts as $account) {
      $debits = Entry::where('debit_id', $account->id)->whereDate('inclusion', '<=', $date)->get();
      $credits = Entry::where('credit_id', $account->id)->whereDate('inclusion', '<=', $date)->get();
      $amount = $credits->sum('value') - $debits->sum('value');

      if(preg_match("/lucro/i", $account->name)) {
        $amount = $amount + $netIncome;
      }

      $balance['equity'][$account->name] = $amount;
      $equity += $amount;
    }
    $balance['amounts']['equity'] = $equity;
    $balance['amounts']['liabilities'] = $currentLiabilities + $longTermLiabilities + $equity;

    $data = [
      "balance" => $balance,
      "incomeStatement" => $incomeStatement,
    ];

    return $data;
  }
}
