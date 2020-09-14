<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $primaryKey = 'id_product';
  protected $table = 'products';
  protected $hidden = [
    'created_at',
    'updated_at'
  ];

  protected $fillable = [
    'image',
    'name',
    'id_product_category',
    'total_perunit',
  ];

  public function category()
  {
    return $this->hasOne('App\ProductCategories', 'id_product_category', 'id_product_category');
  }

  public function price()
  {
    return $this->hasMany('App\PriceCategories', 'id_product');
  }
}
