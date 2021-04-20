<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    public $timestamps = false;
    // define table
    protected $table = 'tbl_trainer';
    // define primary key
    protected $primaryKey = 'id_trainer';
    // define field
    protected $fillable = ['nama_trainer', 'foto_trainer', 'id_cabang'];
}
