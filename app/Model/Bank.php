<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    public $timestamps = false;
    // define table
    protected $table = 'tbl_bank';
    // define primary key
    protected $primaryKey = 'id_bank';
    // define field
    protected $fillable = ['nama_bank', 'rekening', 'id_cabang'];
}
