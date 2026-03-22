<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'user_id'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function group_users()
    {
        return $this->hasMany(GroupUser::class);
    }

    public function tanzabooks()
    {
        return $this->hasMany(Tanzabook::class);
    }
}
