<?php

declare(strict_types=1);

namespace App\Domain\Application\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Domain\Application\Models\ApplicationCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApplicationCategoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ApplicationCategory');
    }

    public function view(AuthUser $authUser, ApplicationCategory $applicationCategory): bool
    {
        return $authUser->can('View:ApplicationCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ApplicationCategory');
    }

    public function update(AuthUser $authUser, ApplicationCategory $applicationCategory): bool
    {
        return $authUser->can('Update:ApplicationCategory');
    }

    public function delete(AuthUser $authUser, ApplicationCategory $applicationCategory): bool
    {
        return $authUser->can('Delete:ApplicationCategory');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ApplicationCategory');
    }

    public function restore(AuthUser $authUser, ApplicationCategory $applicationCategory): bool
    {
        return $authUser->can('Restore:ApplicationCategory');
    }

    public function forceDelete(AuthUser $authUser, ApplicationCategory $applicationCategory): bool
    {
        return $authUser->can('ForceDelete:ApplicationCategory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ApplicationCategory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ApplicationCategory');
    }

    public function replicate(AuthUser $authUser, ApplicationCategory $applicationCategory): bool
    {
        return $authUser->can('Replicate:ApplicationCategory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ApplicationCategory');
    }

}