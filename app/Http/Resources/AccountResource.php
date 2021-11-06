<?php

namespace App\Http\Resources;

use App\Http\Resources\GroupResource;
use App\Http\Resources\SubgroupResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
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
      'group_id' => $this->group_id,
      'subgroup_id' => $this->subgroup_id,
      'name' => $this->name,
      'description' => $this->description,
      'created_at' => $this->created_at->format('Y-m-d'),
      'updated_at' => $this->updated_at->format('Y-m-d'),
      'group' => new GroupResource($this->group),
      'subgroup' => new SubgroupResource($this->subgroup),
    ];
  }
}
