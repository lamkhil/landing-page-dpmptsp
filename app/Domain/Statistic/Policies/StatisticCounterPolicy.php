<?php

declare(strict_types=1);

namespace App\Domain\Statistic\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Domain\Statistic\Models\StatisticCounter;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatisticCounterPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:StatisticCounter');
    }

    public function view(AuthUser $authUser, StatisticCounter $statisticCounter): bool
    {
        return $authUser->can('View:StatisticCounter');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:StatisticCounter');
    }

    public function update(AuthUser $authUser, StatisticCounter $statisticCounter): bool
    {
        return $authUser->can('Update:StatisticCounter');
    }

    public function delete(AuthUser $authUser, StatisticCounter $statisticCounter): bool
    {
        return $authUser->can('Delete:StatisticCounter');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:StatisticCounter');
    }

    public function restore(AuthUser $authUser, StatisticCounter $statisticCounter): bool
    {
        return $authUser->can('Restore:StatisticCounter');
    }

    public function forceDelete(AuthUser $authUser, StatisticCounter $statisticCounter): bool
    {
        return $authUser->can('ForceDelete:StatisticCounter');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:StatisticCounter');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:StatisticCounter');
    }

    public function replicate(AuthUser $authUser, StatisticCounter $statisticCounter): bool
    {
        return $authUser->can('Replicate:StatisticCounter');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:StatisticCounter');
    }

}