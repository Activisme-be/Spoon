<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class UserRepository
 *
 * @package App\Repositories
 */
class UserRepository extends Authenticatable
{
    /**
     * Search query scope for users in the application.
     *
     * @param  Builder $query The eloqunet query builder instance.
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $searchTerm): Builder
    {
        return $query->where('voornaam', 'LIKE', "%{$searchTerm}%")
            ->orWhere('achternaam', 'LIKE', "%{$searchTerm}%")
            ->orWhere('email', 'LIKE', "%{$searchTerm}%");
    }
}
