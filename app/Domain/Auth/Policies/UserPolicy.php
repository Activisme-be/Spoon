<?php

namespace App\Domain\Auth\Policies;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Lab404\Impersonate\Services\ImpersonateManager;

/**
 * Class UserPolicy
 *
 * @package App\Policies
 */
class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user      Entity of the authenticated user.
     * @param  User  $model     Entity of the given user.
     * @return bool
     */
    public function deactivateUser(User $user, User $model): bool
    {
        return $model->isNotBanned() && $user->id !== $model->id;
    }

    /**
     * Determine whether the current user is on the application management kiosk or not.
     *
     * @param  User $user   Entity of the authenticated user.
     * @return bool
     */
    public function onKiosk(User $user): ?bool
    {
        return auth()->check() && $user->on_kiosk;
    }

    /**
     * Determine whether the current user is on the application backend or not.
     *
     * @param  User $user   Entity of the authenticated user.
     * @return bool
     */
    public function onApplication(User $user): bool
    {
        return auth()->check() && ! $this->onKiosk($user);
    }

    /**
     * Determine whether the authenticated user can activate users back in the application.
     *
     * @param  User $user    Entity of the authenticated user.
     * @param  User $model   Entity from the given user.
     * @return bool
     */
    public function activateUser(User $user, User $model): bool
    {
        return $model->isBanned() && $user->id !== $model->id;
    }

    /**
     * Determine whether the authenticated user is the same than the given user.
     *`
     * @param  User $user   Entity of the authenticated user.
     * @param  User $model  Entity of the given user.
     * @return bool
     */
    public function sameUser(User $user, User $model): bool
    {
        return $user->is($model);
    }

    public function impersonate(User $user, User $model, ?string $guardName = null): bool
    {
        $impersonateManager = app()->make(ImpersonateManager::class);
        $guardName = $guardName ?? $impersonateManager->getDefaultSessionGuard();

        return $user->isNot($model)
            && $user->hasRole(UserRoles::WEBMASTER)
            && ($impersonateManager->getCurrentAuthGuardName() === $guardName)
            && ! $impersonateManager->isImpersonating()
            && $user->canImpersonate();
    }

    public function leaveImpersonate(User $user): bool
    {
        $impersonateManager = app()->make(ImpersonateManager::class);

        return $user->hasRole(UserRoles::ADMIN) && $impersonateManager->isImpersonating();
    }
}
