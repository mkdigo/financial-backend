<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Exceptions\ExceptionHandler;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepositoryInterface;

class ProductController extends Controller
{
  private $repository;

  public function __construct(ProductRepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function index()
  {
    $products = $this->repository->get();

    return $this->response([
      'products' => ProductResource::collection($products),
    ]);
  }

  public function store()
  {
    $product = $this->repository->store();

    return $this->response([
      'product' => new ProductResource($product),
    ], 201);
  }

  public function update(Product $product)
  {
    $product = $this->repository->update($product);

    return $this->response([
      'product' => new ProductResource($product),
    ]);
  }

  public function destroy(Product $product)
  {
    $product = $this->repository->delete($product);

    return $this->response();
  }
}
