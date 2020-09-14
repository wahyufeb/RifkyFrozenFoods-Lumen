<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cashier extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

  protected $primaryKey = 'id_cashier';
  protected $table = 'cashier';
  protected $hidden = [
    'password',
    'created_at',
    'updated_at'
  ];
  protected $fillable = [
    'username',
    'photo',
    'name',
    'password',
    'id_product_storage',
    'id_store'
  ];

  public function store()
  {
    return $this->hasOne('App\Stores', 'id_store', 'id_store');
  }
}
