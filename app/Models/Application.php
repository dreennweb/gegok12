<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    protected $fillable = [
        'business_id',
        'reference_number',
        'status',
        'global_progress',
        'submitted_at',
        'completed_at',
        'metadata',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'completed_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function departmentApplications(): HasMany
    {
        return $this->hasMany(DepartmentApplication::class);
    }

    public function queries(): HasMany
    {
        return $this->hasMany(ApplicationQuery::class, 'department_application_id')
            ->through('departmentApplications');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function calculateProgress(): int
    {
        $total = $this->departmentApplications()->count();
        if ($total === 0) return 0;

        $approved = $this->departmentApplications()
            ->where('status', 'approved')
            ->count();

        return (int) (($approved / $total) * 100);
    }
}
