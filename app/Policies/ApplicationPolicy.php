<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Application;

class ApplicationPolicy
{
    /**
     * Determine if user can view application
     */
    public function view(User $user, Application $application): bool
    {
        // Applicant can view their own application
        if ($user->id === $application->business->user_id) {
            return true;
        }

        // Department staff/executive can view if assigned
        if ($user->departments()->exists()) {
            return $application->departmentApplications()
                ->whereIn('department_id', $user->departments()->pluck('id'))
                ->exists();
        }

        // Superadmin can view all
        return $user->hasRole('superadmin');
    }

    /**
     * Determine if user can update application
     */
    public function update(User $user, Application $application): bool
    {
        // Only applicant can update draft applications
        if ($application->status === 'draft') {
            return $user->id === $application->business->user_id;
        }

        return false;
    }

    /**
     * Determine if user can manage department application
     */
    public function manageDepartmentApplication(User $user, Application $application): bool
    {
        // Department executives and staff can manage
        return $user->departments()->exists() || $user->hasRole('superadmin');
    }
}
