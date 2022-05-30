<?php

namespace App\Repositories;

use App\Models\Account;

interface AccountRepositoryInterface
{
  public function get();
  public function store();
  public function update(Account $account);
  public function delete(Account $account);
}
