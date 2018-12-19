<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{
  use Notifiable;

  protected $table = 'tbl_client';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    //'name', 'email', 'password', 'role', 'store_id',
    //'name', 'login_id', 'login_password',
    'login_id', 'login_password',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'access_key',
  ];

  public function setRememberToken($value) {
    return $this;
  }
}
