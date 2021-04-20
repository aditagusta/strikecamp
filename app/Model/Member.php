<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    public $timestamps = false;
    // define table
    protected $table = 'tbl_member';
    // define primary key
    protected $primaryKey = 'id_member';
    // define field
    protected $fillable = ['username', 'password', 'password1', 'nama_member', 'telepon', 'id_cabang', 'gambar_member'];
}
