<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    public $timestamps = false;
    // define table
    protected $table = 'tbl_banner';
    // define primary key
    protected $primaryKey = 'id_banner';
    // define field
    protected $fillable = ['foto'];
}
