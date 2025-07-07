<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prq extends Model
{
    protected $fillable = [
        'id_mun',
        'parroquia'
    ];

    protected $table = 'prqs';
    protected $primaryKey = 'id_prq';
    public $incrementing = false;
}
