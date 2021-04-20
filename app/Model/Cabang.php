<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    public $timestamps = false;
    // define table
    protected $table = 'tbl_cabang';
    // define primary key
    protected $primaryKey = 'id_cabang';
    // define field
    protected $fillable = ['nama_cabang', 'lokasi', 'telepon', 'gambar_cabang'];
}
