<?php

namespace App\Repositories;

use App\Helpers\Helper;
use App\Models\Product;
use Illuminate\Validation\Rule;
use App\Exceptions\ExceptionHandler;
use Illuminate\Support\Facades\Validator;

class ProductRepository implements ProductRepositoryInterface
{
  private function validator(Product $product = null)
  {
    $data = request()->only('provider_id', 'barcode', 'ref', 'name', 'description', 'cost', 'price', 'note');

    $rules = [
      'provider_id' => 'required|integer|exists:providers,id',
      'barcode' => 'nullable|string|unique:products',
      'ref' => 'nullable|string|max:191',
      'name' => 'required|string|max:191',
      'description' => 'nullable|string',
      'cost' => 'required|integer',
      'price' => 'required|integer',
      'note' => 'nullable|string',
    ];

    if($product) {
      $rules['barcode'] = [
        'nullable',
        'integer',
        Rule::unique('products')->ignore($product->id),
      ];
    }

    Helper::validator($data, $rules);

    return $data;
  }

  public function get()
  {
    $data = request()->only('search', 'barcode', 'ref', 'price');

    $rules = [
      'search' => 'nullable|string',
      'barcode' => 'nullable|integer',
      'ref' => 'nullable|string',
      'price' => 'nullable|integer',
    ];

    Helper::validator($data, $rules);

    $products = Product::where(function($query) {
      $query->where('name', 'like', "%". request()->search ."%")
        ->orWhere(function($query) {
          $query->where('description', 'like', "%". request()->search ."%")
            ->orWhere('note', 'like', "%". request()->search ."%");
        });
    })
    ->when(request()->barcode, function($query) {
      $query->where('barcode', request()->barcode);
    })
    ->when(request()->ref, function($query) {
      $query->where('ref', request()->ref);
    })
    ->when(request()->price, function($query) {
      $query->where('price', '<=', request()->price);
    })
    ->get();

    return $products;
  }

  public function store()
  {
    $data = $this->validator();

    $product = Product::create($data);
    $product->refresh();

    return $product;
  }

  public function update(Product $product)
  {
    $data = $this->validator($product);

    $product->update($data);
    $product->refresh();

    return $product;
  }

  public function delete(Product $product)
  {
    $product->delete();

    return true;
  }
}
