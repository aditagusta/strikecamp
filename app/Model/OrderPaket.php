<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderPaket extends Model
{
    public $timestamps = false;
    // define table
    protected $table = 'tbl_order';
    // define primary key
    protected $primaryKey = 'id_order';
    // define field
    protected $fillable = ['id_user', 'id_member', 'jumlah_paket', 'tanggal_order', 'id_cabang'];
}
