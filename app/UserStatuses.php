<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserStatuses extends Model
{
    public $timestamps = false;

    public function user()
    {
        return $this->hasMany(\App\User::class, 'status_id');
    }
}
