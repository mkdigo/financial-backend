<?php

namespace App\Repositories;

interface EntryRepositoryInterface
{
  public function get();
  public function store();
  public function update(int $id);
  public function delete(int $id);
  public function getExpenses();
}
