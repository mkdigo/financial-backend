<?php

namespace App\Repositories;

use App\Models\Entry;

interface EntryRepositoryInterface
{
  public function get();
  public function store();
  public function update(Entry $entry);
  public function delete(Entry $entry);
  public function getExpenses();
}
