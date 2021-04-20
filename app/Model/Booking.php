<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public $timestamps = false;
    // define table
    protected $table = 'tbl_booking';
    // define primary key
    protected $primaryKey = 'id_booking';
    // define field
    protected $fillable = ['id_user','id_member','id_jadwal','id_jam','id_cabang'];
}
