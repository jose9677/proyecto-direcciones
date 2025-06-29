<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'desdcription',
        'active'
    ];

    protected $table = 'roles';
    protected $primaryKey = 'id_rol';
}
