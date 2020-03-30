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
    public function scopeSearch(Builder $query, string $searchTerm): Builder
    {
        return $query->where('voornaam', 'LIKE', "%{$searchTerm}%")
            ->orWhere('achternaam', 'LIKE', "%{$searchTerm}%")
            ->orWhere('email', 'LIKE', "%{$searchTerm}%");
    }
}
