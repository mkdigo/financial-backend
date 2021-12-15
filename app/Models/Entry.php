<?php

namespace App\Models;

use App\Models\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entry extends Model
{
  use HasFactory;

  protected $fillable = [
    'inclusion',
    'debit_id',
    'credit_id',
    'value',
    'note',
  ];

  protected $casts = [
    'debit_id' => 'integer',
    'credit_id' => 'integer',
    'value' => 'integer',
  ];

  public function debitAccount()
  {
    return $this->belongsTo(Account::class, 'debit_id');
  }

  public function creditAccount()
  {
    return $this->belongsTo(Account::class, 'credit_id');
  }
}
