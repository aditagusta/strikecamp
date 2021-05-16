<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    public $timestamps = false;
    // define table
    protected $table = 'tbl_jadwal';
    // define primary key
    protected $primaryKey = 'id_jadwal';
    // define field
    protected $fillable = ['id_user', 'jadwal', 'id_cabang', 'id_trainer','id_jam'];

    public function trainers(){
        return $this->hasMany('App\Model\JadwalTrainer', 'id_jadwal', 'id_jadwal');
    }
}
