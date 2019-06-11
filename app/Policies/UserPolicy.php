<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class UserPolicy
 *
 *
 * @package App\Policies
 */
class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user  Entity of the authenticated user.
     * @param  User  $model Entity of the given user.
     * @return bool
     */
    public function deactivateUser(User $user, User $model): bool
    {
        return $user->id !== $model->id && $user->hasanyrole('admin|webmaster');
    }

    /**
     * Determine whether the authenticated user can activate users back in the application.
     *
     * @param  User $user   Entity of the authenticated user.
     * @param  User $model  Entity from the given user.
     * @return bool
     */
    public function activateUser(User $user, User $model): bool
    {
        return $user->id !== $model->id;
    }

    /**
     * Determine whether the authenticated user is the same than the given user.
     *
     * @param  User $user  Entity of the authenticated user.
     * @param  User $model Entity of the given user.
     * @return bool
     */
    public function sameUser(User $user, User $model): bool
    {
        return $user->is($model);
    }

    /**
     * Determine whether the authenticated user can edit the given user or not.
     *
     * @param  User $user   Entity of the authenticated user.
     * @param  User $model  Entity of the given user.
     * @return bool
     */
    public function canEdit(User $user, User $model): bool
    {
        return $user->is($model);
    }
}
