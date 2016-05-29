<?php

namespace App\MultiAuth\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserOauth extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['token', 'social_id', 'token_secret', 'type', 'expires_at'];

    protected $table = 'user_oauths';

    protected $dates = ['expires_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
