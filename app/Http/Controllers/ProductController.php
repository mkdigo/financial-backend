<?php

namespace App\Http\Controllers;

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
    try {
      $products = $this->repository->get();

      return response()->json([
        'success' => true,
        'products' => ProductResource::collection($products),
      ]);
    } catch(ExceptionHandler $e) {
      return $this->errorHandler($e);
    }
  }

  public function store()
  {
    try {
      $product = $this->repository->store();

      return response()->json([
        'success' => true,
        'product' => new ProductResource($product),
      ]);
    } catch(ExceptionHandler $e) {
      return $this->errorHandler($e);
    }
  }

  public function update($id)
  {
    try {
      $product = $this->repository->update((int) $id);

      return response()->json([
        'success' => true,
        'product' => new ProductResource($product),
      ]);
    } catch(ExceptionHandler $e) {
      return $this->errorHandler($e);
    }
  }

  public function destroy($id)
  {
    try {
      $product = $this->repository->delete((int) $id);

      return response()->json([
        'success' => true,
      ]);
    } catch(ExceptionHandler $e) {
      return $this->errorHandler($e);
    }
  }
}
