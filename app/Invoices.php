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
    'id_store',
    'date',
    'total',
    'buyer_money',
    'change_money',
  ];

  public function cashier()
  {
    return $this->hasOne('App\Cashier', 'id_cashier', 'id_cashier');
  }

  public function store()
  {
    return $this->hasOne('App\Stores', 'id_store', 'id_store');
  }

  public function transactions()
  {
    return $this->hasMany('App\Transactions', 'id_invoice', 'id_invoice');
  }
}
