<?php

namespace App\Traits;

use App\Models\Department;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait BelongsToDepartments
{
    /**
     * Get departments user belongs to
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'department_users')
            ->withPivot('role', 'is_active', 'assigned_at')
            ->withTimestamps();
    }

    /**
     * Check if user is active in a department
     */
    public function isActiveDepartmentMember(Department $department): bool
    {
        return $this->departments()
            ->where('department_id', $department->id)
            ->wherePivot('is_active', true)
            ->exists();
    }

    /**
     * Get user role in department
     */
    public function getDepartmentRole(Department $department): ?string
    {
        return $this->departments()
            ->where('department_id', $department->id)
            ->first()?->pivot->role;
    }
}
