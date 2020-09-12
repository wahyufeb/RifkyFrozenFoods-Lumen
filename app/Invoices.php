<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

  protected $primaryKey = 'id_invoice';
  protected $table = 'invoices';
  protected $hidden = [
    'created_at',
    'updated_at'
  ];
  protected $fillable = [
    'id_cashier',
    'date',
    'total',
    'buyer_money',
    'change_money',
  ];
}
