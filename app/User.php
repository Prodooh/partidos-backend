<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surnames', 'email', 'password', 'verification_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'verification_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*  MUTATORS */
    public function setNameAttribute($name) {
        $this->attributes['name'] = mb_strtolower($name);
    }

    public function setSurnamesAttribute($surnames) {
        $this->attributes['surnames'] = mb_strtolower($surnames);
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function phrases()
    {
        return $this->hasMany( Phrase::class );
    }

    /**
     * Return if user is verified
     *
     *
     */
    // public function isVerified()
    // {
    //     return $this->email_verified_at ? true : false;
    // }

    /**
     * Generate verification token
     *
     *
     */
    // static public function generateVerificationToken()
    // {
    //     return bin2hex(random_bytes(40));
    // }
}
