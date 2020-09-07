<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    public $timestamps = false;

    public function user()
    {
        return $this->hasMany(\App\User::class, 'role_id');
    }
}
