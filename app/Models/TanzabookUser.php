<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TanzabookUser extends Model
{
    protected $fillable = [
        'tanzabook_id',
        'user_id',
        'shared_type'
    ];

    public function tanzabook()
    {
        return $this->belongsTo(Tanzabook::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
