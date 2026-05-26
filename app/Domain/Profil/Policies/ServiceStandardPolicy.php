<?php

declare(strict_types=1);

namespace App\Domain\Profil\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Domain\Profil\Models\ServiceStandard;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceStandardPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ServiceStandard');
    }

    public function view(AuthUser $authUser, ServiceStandard $serviceStandard): bool
    {
        return $authUser->can('View:ServiceStandard');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ServiceStandard');
    }

    public function update(AuthUser $authUser, ServiceStandard $serviceStandard): bool
    {
        return $authUser->can('Update:ServiceStandard');
    }

    public function delete(AuthUser $authUser, ServiceStandard $serviceStandard): bool
    {
        return $authUser->can('Delete:ServiceStandard');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ServiceStandard');
    }

    public function restore(AuthUser $authUser, ServiceStandard $serviceStandard): bool
    {
        return $authUser->can('Restore:ServiceStandard');
    }

    public function forceDelete(AuthUser $authUser, ServiceStandard $serviceStandard): bool
    {
        return $authUser->can('ForceDelete:ServiceStandard');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ServiceStandard');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ServiceStandard');
    }

    public function replicate(AuthUser $authUser, ServiceStandard $serviceStandard): bool
    {
        return $authUser->can('Replicate:ServiceStandard');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ServiceStandard');
    }

}