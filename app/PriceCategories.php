<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceCategories extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

  protected $primaryKey = 'id_price_category';
  protected $table = 'price_category';
  protected $hidden = [
    'created_at',
    'updated_at'
  ];
  protected $fillable = [
    'id_product',
    'name',
    'price'
  ];

  public function products()
  {
    return $this->belongsTo('App\Products', 'id_product', 'id_product');
  }
}
