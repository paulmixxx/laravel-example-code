<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function activateToken()
    {
        return $this->hasOne(\App\UserActivateTokenByEmail::class);
    }

    public function role()
    {
        return $this->belongsTo(\App\UserRoles::class);
    }

    public function status()
    {
        return $this->belongsTo(\App\UserStatuses::class);
    }

    public function isAdmin()
    {
        return $this->role->code === "admin";
    }
}
