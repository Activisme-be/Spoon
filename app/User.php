<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Cog\Contracts\Ban\Bannable as BannableContract;
use Cog\Laravel\Ban\Traits\Bannable;

/**
 * Class User 
 * 
 * @package App
 */
class User extends Authenticatable implements BannableContract
{
    use Notifiable, Bannable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['voornaam', 'achternaam', 'email', 'password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the user's name.
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        return ucfirst($this->voornaam) . ' ' . ucfirst($this->achternaam);
    }
}
