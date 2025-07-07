<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mun extends Model
{
    protected $fillable = [
        'id_edo',
        'municipio'
    ];

    protected $table = 'muns';
    protected $primaryKey = 'id_mun';
    public $incrementing = false;
}
