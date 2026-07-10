<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationQuery extends Model
{
    protected $fillable = [
        'department_application_id',
        'queried_by',
        'query_description',
        'required_documents',
        'status',
        'responded_at',
        'response_notes',
        'resolved_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
        'resolved_at' => 'datetime',
        'required_documents' => 'array',
    ];

    public function departmentApplication(): BelongsTo
    {
        return $this->belongsTo(DepartmentApplication::class);
    }

    public function queriedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'queried_by');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }
}
