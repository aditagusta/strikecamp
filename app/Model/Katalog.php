<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Katalog extends Model
{
    public $timestamps = false;
    // define table
    protected $table = 'tbl_katalog';
    // define primary key
    protected $primaryKey = 'id_katalog';
    // define field
    protected $fillable = ['nama_katalog,deskripsi,foto'];
}
