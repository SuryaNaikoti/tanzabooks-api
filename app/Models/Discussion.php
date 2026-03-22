<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discussion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'discussable_id',
        'discussable_type',
        'commentable_id',
        'commentable_type',
        'file_id',
        'comment'
    ];

    protected $casts = [
        'json' => 'json'
    ];

    public function discussable()
    {
        return $this->morphTo();
    }

    public function commentable()
    {
        return $this->morphTo();
    }
}
