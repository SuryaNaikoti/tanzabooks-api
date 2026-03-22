<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Upload extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'fileable_type',
        'fileable_id',
        'file_url',
        'file_original_name',
        'file_name',
        'file_size',
        'extension',
        'type'
    ];

    public function fileable()
    {
        return $this->morphTo()->latest();
    }

}
