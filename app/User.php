<?php

namespace App;

use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Cog\Contracts\Ban\Bannable as BannableContract;
use Cog\Laravel\Ban\Traits\Bannable;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User 
 * 
 * @package App
 */
class User extends Authenticatable implements BannableContract
{
    use Notifiable, Bannable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['voornaam', 'achternaam', 'email', 'password', 'last_login_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'banned_at', 'updated_at', 'last_login_at'];


    /**
     * Method for tracking if the user or not. 
     * 
     * @return bool
     */
    public function isOnline(): bool 
    {
        return Cache::has('user-is-online-' . $this->id);
    }

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
