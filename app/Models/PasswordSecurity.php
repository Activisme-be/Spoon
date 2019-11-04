<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PasswordSecurity
 *
 * @package App\Models
 */
class PasswordSecurity extends Model
{
    /**
     * The guarded fields for the database table.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Data relation for the user that is attached to the password securities.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
