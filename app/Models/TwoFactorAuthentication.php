<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TwoFactorAuthentication
 *
 * @package App\Models
 */
class TwoFactorAuthentication extends Model
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
