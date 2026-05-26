<?php

declare(strict_types=1);

namespace App\Domain\Content\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Domain\Content\Models\Infographic;
use Illuminate\Auth\Access\HandlesAuthorization;

class InfographicPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Infographic');
    }

    public function view(AuthUser $authUser, Infographic $infographic): bool
    {
        return $authUser->can('View:Infographic');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Infographic');
    }

    public function update(AuthUser $authUser, Infographic $infographic): bool
    {
        return $authUser->can('Update:Infographic');
    }

    public function delete(AuthUser $authUser, Infographic $infographic): bool
    {
        return $authUser->can('Delete:Infographic');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Infographic');
    }

    public function restore(AuthUser $authUser, Infographic $infographic): bool
    {
        return $authUser->can('Restore:Infographic');
    }

    public function forceDelete(AuthUser $authUser, Infographic $infographic): bool
    {
        return $authUser->can('ForceDelete:Infographic');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Infographic');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Infographic');
    }

    public function replicate(AuthUser $authUser, Infographic $infographic): bool
    {
        return $authUser->can('Replicate:Infographic');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Infographic');
    }

}