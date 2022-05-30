<?php

namespace App\Repositories;

use App\Models\Product;

interface ProductRepositoryInterface
{
  public function get();
  public function store();
  public function update(Product $product);
  public function delete(Product $product);
}
