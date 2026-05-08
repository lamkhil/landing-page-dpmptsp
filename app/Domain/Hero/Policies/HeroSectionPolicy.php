<?php

declare(strict_types=1);

namespace App\Domain\Hero\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Domain\Hero\Models\HeroSection;
use Illuminate\Auth\Access\HandlesAuthorization;

class HeroSectionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:HeroSection');
    }

    public function view(AuthUser $authUser, HeroSection $heroSection): bool
    {
        return $authUser->can('View:HeroSection');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:HeroSection');
    }

    public function update(AuthUser $authUser, HeroSection $heroSection): bool
    {
        return $authUser->can('Update:HeroSection');
    }

    public function delete(AuthUser $authUser, HeroSection $heroSection): bool
    {
        return $authUser->can('Delete:HeroSection');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:HeroSection');
    }

    public function restore(AuthUser $authUser, HeroSection $heroSection): bool
    {
        return $authUser->can('Restore:HeroSection');
    }

    public function forceDelete(AuthUser $authUser, HeroSection $heroSection): bool
    {
        return $authUser->can('ForceDelete:HeroSection');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:HeroSection');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:HeroSection');
    }

    public function replicate(AuthUser $authUser, HeroSection $heroSection): bool
    {
        return $authUser->can('Replicate:HeroSection');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:HeroSection');
    }

}