<?php

namespace App\Http\Resources;

use App\Http\Resources\GroupResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SubgroupResource extends JsonResource
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
      'description' => $this->description,
      'group' => new GroupResource($this->group),
    ];
  }
}
