<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tanzabook extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'folder_id',
        'group_id',
        'name',
        'file_id',
        'file_path',
        'status'
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, TanzabookUser::class);
    }

    public function annotations()
    {
        return $this->hasMany(Annotation::class);
    }

    public function discussions()
    {
        return $this->morphMany(Discussion::class, 'discussable');
    }

}
