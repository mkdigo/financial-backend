<?php

namespace App\Http\Resources;

use App\Http\Resources\ProviderResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
  /**
  * Transform the resource into an array.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
  */
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'barcode' => $this->barcode,
      'ref' => $this->ref,
      'name' => $this->name,
      'description' => $this->description,
      'cost' => $this->cost,
      'price' => $this->price,
      'note' => $this->note,
      'quantity' => $this->quantity,
      'provider' => new ProviderResource($this->provider),
    ];
  }
}
