<?php

declare(strict_types=1);

namespace App\Domain\Profil\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Domain\Profil\Models\OrgUnit;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrgUnitPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:OrgUnit');
    }

    public function view(AuthUser $authUser, OrgUnit $orgUnit): bool
    {
        return $authUser->can('View:OrgUnit');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:OrgUnit');
    }

    public function update(AuthUser $authUser, OrgUnit $orgUnit): bool
    {
        return $authUser->can('Update:OrgUnit');
    }

    public function delete(AuthUser $authUser, OrgUnit $orgUnit): bool
    {
        return $authUser->can('Delete:OrgUnit');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:OrgUnit');
    }

    public function restore(AuthUser $authUser, OrgUnit $orgUnit): bool
    {
        return $authUser->can('Restore:OrgUnit');
    }

    public function forceDelete(AuthUser $authUser, OrgUnit $orgUnit): bool
    {
        return $authUser->can('ForceDelete:OrgUnit');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:OrgUnit');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:OrgUnit');
    }

    public function replicate(AuthUser $authUser, OrgUnit $orgUnit): bool
    {
        return $authUser->can('Replicate:OrgUnit');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:OrgUnit');
    }

}