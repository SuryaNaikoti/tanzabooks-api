<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Annotation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tanzabook_id',
        'user_id',
        'file',
        'type',
        'data',
        'comment'
    ];

    public function tanzabook()
    {
        return $this->belongsTo(Tanzabook::class);
    }

    public function comments()
    {
        return $this->morphMany(Discussion::class, 'discussable');
    }

}
