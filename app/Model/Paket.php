<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    public $timestamps = false;
    // define table
    protected $table = 'tbl_paket';
    // define primary key
    protected $primaryKey = 'id_paket';
    // define field
    protected $fillable = ['nama_paket,jumlah,harga'];
}
