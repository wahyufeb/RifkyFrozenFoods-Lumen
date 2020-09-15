<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

  protected $primaryKey = 'id_admin';
  protected $table = 'admin';
  protected $hidden = [
    'password',
    'created_at',
    'updated_at'
  ];
  protected $fillable = [
    'photo',
    'username',
    'name',
    'password',
  ];
}
