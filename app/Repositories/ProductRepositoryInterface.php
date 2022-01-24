<?php

namespace App\Repositories;

interface ProductRepositoryInterface
{
  public function get();
  public function store();
  public function update(int $id);
  public function delete(int $id);
}
