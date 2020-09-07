<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserActivateTokenByEmail extends Model
{
    public $timestamps = false;
    protected $fillable = ['token', 'expire_time'];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }
}
