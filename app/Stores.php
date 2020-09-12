<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stores extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

  protected $primaryKey = 'id_store';
  protected $table = 'stores';
  protected $hidden = [
    'created_at',
    'updated_at'
  ];
  protected $fillable = [
    'name', 'location'
  ];
}
