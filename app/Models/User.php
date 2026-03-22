<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'mobile',
        'remember_token',
        'institute_name'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    public function tanzabooks()
    {
        return $this->hasManyThrough(Tanzabook::class, Folder::class);
    }

    public function groupMember()
    {
        return $this->hasMany(GroupUser::class);
//        return $this->hasManyThrough(GroupUser::class, Group::class);
    }

    public function groupOwner()
    {
        return $this->hasMany(Group::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class)->latest();
    }
}
