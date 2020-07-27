<?php

namespace App\Models;

use App\Support\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TwoFactorAuthentication
 *
 * @package App\Models
 */
class TwoFactorAuthentication extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['google2fa_recovery_tokens' => 'array'];

    /**
     * Data relation for the user that is attached to the password securities.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
