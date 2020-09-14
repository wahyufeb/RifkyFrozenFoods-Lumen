<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductStorage extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

  protected $primaryKey = 'id_product_storage';
  protected $table = 'product_storage';
  protected $hidden = [
    'created_at',
    'updated_at'
  ];
  protected $fillable = [
    'id_product',
    'id_store',
    'stock'
  ];

  public function product()
  {
    return $this->hasOne('App\Products', 'id_product', 'id_product');
  }

  public function store()
  {
    return $this->hasOne('App\Stores', 'id_store', 'id_store');
  }
}
