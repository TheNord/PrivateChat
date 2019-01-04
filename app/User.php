<?php

namespace App;

use App\Models\Session;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'blocked',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function block()
    {
        $this->blocked = true;
        $this->save();
    }

    public function unblock()
    {
        $this->blocked = false;
        $this->save();
    }

    public function isBlocked()
    {
        return $this->blocked;
    }
}
