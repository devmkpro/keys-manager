<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Credential;
use App\Models\User;

class CredentialPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Credential');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Credential $credential): bool
    {
        return $user->checkPermissionTo('view Credential');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Credential');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Credential $credential): bool
    {
        return $user->checkPermissionTo('update Credential');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Credential $credential): bool
    {
        return $user->checkPermissionTo('delete Credential');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Credential');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Credential $credential): bool
    {
        return $user->checkPermissionTo('restore Credential');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Credential');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Credential $credential): bool
    {
        return $user->checkPermissionTo('replicate Credential');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Credential');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Credential $credential): bool
    {
        return $user->checkPermissionTo('force-delete Credential');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Credential');
    }
}
