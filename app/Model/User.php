<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;
    protected $guard = 'pusat';
    public $timestamps = false;
    // define table
    protected $table = 'tbl_user';
    // define primary key
    protected $primaryKey = 'id_user';
    // define field
    protected $fillable = ['username', 'password', 'password1', 'nama_user', 'level', 'telepon', 'id_cabang'];
}
