<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategories extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $primaryKey = 'id_product_category';
  protected $table = 'product_category';
  protected $hidden = [
    'created_at',
    'updated_at'
  ];
  protected $fillable = [
    'name'
  ];
}
