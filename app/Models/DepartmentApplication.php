<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DepartmentApplication extends Model
{
    protected $fillable = [
        'application_id',
        'department_id',
        'status',
        'assigned_to',
        'existing_registration_number',
        'query_notes',
        'rejection_reason',
        'certificate_number',
        'certificate_issued_at',
        'department_specific_data',
        'progress',
    ];

    protected $casts = [
        'certificate_issued_at' => 'datetime',
        'department_specific_data' => 'array',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function queries(): HasMany
    {
        return $this->hasMany(ApplicationQuery::class);
    }

    public function openQueries(): HasMany
    {
        return $this->queries()->where('status', 'open');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeQueried($query)
    {
        return $query->where('status', 'queried');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
