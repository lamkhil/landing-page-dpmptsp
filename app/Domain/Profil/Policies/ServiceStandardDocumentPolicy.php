<?php

declare(strict_types=1);

namespace App\Domain\Profil\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Domain\Profil\Models\ServiceStandardDocument;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceStandardDocumentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ServiceStandardDocument');
    }

    public function view(AuthUser $authUser, ServiceStandardDocument $serviceStandardDocument): bool
    {
        return $authUser->can('View:ServiceStandardDocument');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ServiceStandardDocument');
    }

    public function update(AuthUser $authUser, ServiceStandardDocument $serviceStandardDocument): bool
    {
        return $authUser->can('Update:ServiceStandardDocument');
    }

    public function delete(AuthUser $authUser, ServiceStandardDocument $serviceStandardDocument): bool
    {
        return $authUser->can('Delete:ServiceStandardDocument');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ServiceStandardDocument');
    }

    public function restore(AuthUser $authUser, ServiceStandardDocument $serviceStandardDocument): bool
    {
        return $authUser->can('Restore:ServiceStandardDocument');
    }

    public function forceDelete(AuthUser $authUser, ServiceStandardDocument $serviceStandardDocument): bool
    {
        return $authUser->can('ForceDelete:ServiceStandardDocument');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ServiceStandardDocument');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ServiceStandardDocument');
    }

    public function replicate(AuthUser $authUser, ServiceStandardDocument $serviceStandardDocument): bool
    {
        return $authUser->can('Replicate:ServiceStandardDocument');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ServiceStandardDocument');
    }

}