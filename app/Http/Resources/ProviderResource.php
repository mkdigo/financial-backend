<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProviderResource extends JsonResource
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
      'name' => $this->name,
      'email' => $this->email,
      'phone' => $this->phone,
      'cellphone' => $this->cellphone,
      'zipcode' => $this->zipcode,
      'state' => $this->state,
      'city' => $this->city,
      'address' => $this->address,
      'note' => $this->note,
      'created_at' => $this->created_at->format('Y-m-d'),
      'updated_at' => $this->created_at->format('Y-m-d'),
    ];
  }
}
