<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

  protected $primaryKey = 'id_transaction';
  protected $table = 'transactions';
  protected $hidden = [
    'created_at',
    'updated_at'
  ];
  protected $fillable = [
    'id_invoice',
    'id_product',
    'qty',
    'subtotal'
  ];

  public function invoices()
  {
    return $this->belongsTo('App\Invoices', 'id_invoice', 'id_invoice');
  }

  public function product()
  {
    return $this->hasOne('App\Products', 'id_product', 'id_product');
  }
}
