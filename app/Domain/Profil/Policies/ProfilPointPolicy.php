<?php

declare(strict_types=1);

namespace App\Domain\Profil\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Domain\Profil\Models\ProfilPoint;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilPointPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ProfilPoint');
    }

    public function view(AuthUser $authUser, ProfilPoint $profilPoint): bool
    {
        return $authUser->can('View:ProfilPoint');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ProfilPoint');
    }

    public function update(AuthUser $authUser, ProfilPoint $profilPoint): bool
    {
        return $authUser->can('Update:ProfilPoint');
    }

    public function delete(AuthUser $authUser, ProfilPoint $profilPoint): bool
    {
        return $authUser->can('Delete:ProfilPoint');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ProfilPoint');
    }

    public function restore(AuthUser $authUser, ProfilPoint $profilPoint): bool
    {
        return $authUser->can('Restore:ProfilPoint');
    }

    public function forceDelete(AuthUser $authUser, ProfilPoint $profilPoint): bool
    {
        return $authUser->can('ForceDelete:ProfilPoint');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ProfilPoint');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ProfilPoint');
    }

    public function replicate(AuthUser $authUser, ProfilPoint $profilPoint): bool
    {
        return $authUser->can('Replicate:ProfilPoint');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ProfilPoint');
    }

}