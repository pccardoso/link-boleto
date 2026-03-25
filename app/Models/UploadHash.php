<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadHash extends Model
{
    protected $table = 'upload_hash';

    protected $fillable = [
        'path',
        'hash_plate_id',
    ];

    public function hashPlate()
    {
        return $this->belongsTo(HashPlate::class, 'hash_plate_id', 'id');
    }

}
