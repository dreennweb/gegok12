<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'legal_name',
        'business_type',
        'ntn',
        'cnic',
        'registration_number',
        'business_description',
        'status',
        'business_nature',
        'selected_departments',
        'has_workers',
        'worker_count',
        'metadata',
    ];

    protected $casts = [
        'has_workers' => 'boolean',
        'business_nature' => 'array',
        'selected_departments' => 'array',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('business_type', $type);
    }

    public function scopeWithWorkers($query)
    {
        return $query->where('has_workers', true);
    }
}
