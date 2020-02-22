<?php

namespace App\Models;

use App\Model;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PasswordSecurity
 *
 * @package App\Models
 */
class PasswordSecurity extends Model
{
    protected $guarded = ['id'];

    /**
     * Data relation for the user that is attached to the password securities.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
