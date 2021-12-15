<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EntryResource extends JsonResource
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
      'inclusion' => $this->inclusion,
      'debit_id' => $this->debit_id,
      'debit_name' => $this->debitAccount->name,
      'credit_id' => $this->credit_id,
      'credit_name' => $this->creditAccount->name,
      'value' => $this->value,
      'note' => $this->note,
      'created_at' => $this->created_at->format('Y-m-d'),
      'updated_at' => $this->updated_at->format('Y-m-d'),
    ];
  }
}
