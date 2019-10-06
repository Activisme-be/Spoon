<?php

namespace App\Models;

use App\Repositories\UserRepository;
use App\Traits\ActivityLog;
use Cog\Contracts\Ban\Bannable as BannableContract;
use Cog\Laravel\Ban\Traits\Bannable;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User.
 */
class User extends UserRepository implements BannableContract
{
    use Notifiable, Bannable, HasRoles, ActivityLog, CausesActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['voornaam', 'on_kiosk', 'achternaam', 'email', 'password', 'last_login_at'];

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
     * Method for tracking if the user online or not.
     *
     * @return bool
     */
    public function isOnline(): bool
    {
        return Cache::has('user-is-online-'.$this->id);
    }

    /**
     * Method for hashing the given password in the application storage.
     *
     * @param  string $password The given or generated password from the application/form
     * @return void
     */
    public function setPasswordAttribute(string $password): void
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Data relation for the 2FA password securities.
     *
     * @return HasOne
     */
    public function passwordSecurity(): HasOne
    {
        return $this->hasOne(PasswordSecurity::class);
    }

    /**
     * Get the user's name.
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        return ucfirst($this->voornaam).' '.ucfirst($this->achternaam);
    }
}
