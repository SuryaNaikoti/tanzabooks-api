<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'user_id'
    ];

    public function tanzabooks()
    {
        return $this->hasMany(Tanzabook::class, 'folder_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
