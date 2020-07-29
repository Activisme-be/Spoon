<?php

namespace App\Domain\Announcements\Models;

use App\Domain\Auth\Models\User;
use App\Support\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class SystemAlert
 *
 * @package App\Models
 */
class SystemAlert extends Model
{
    /**
     * The guarded mass-assignment fields for the database table.
     *
     * @return array
     */
    protected $guarded = ['id'];

    /**
     * Method for getting the information about who sended the notification.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault(['name' => config('app.name')]);
    }
}
