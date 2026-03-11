<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HashPlate extends Model
{
    
    protected $table = "hash_plate";

    protected $fillable = [
        'id',
        'plate',
        'hash'
    ];

}
