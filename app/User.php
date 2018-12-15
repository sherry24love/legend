<?php

namespace App;

use Illuminate\Notifications\Notifiable;
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
        'name', 'email', 'password', 'rec_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function recuser() {
    	return $this->belongsTo( User::class , 'rec_id' , 'id') ;
    }

    public function rsync() {
    	return $this->hasOne( \App\Models\UserRsync::class , 'user_id' , 'id') ;
    }
}
