<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Edo extends Model
{
    protected $fillable = [
        'estado'
    ];

    protected $table = 'edos';
    protected $primaryKey = 'id_edo';
    public $incrementing = false;
}
