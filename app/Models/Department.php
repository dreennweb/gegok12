<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Department extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'code',
        'description',
        'contact_email',
        'contact_phone',
        'metadata',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'department_users')
            ->withPivot('role', 'is_active', 'assigned_at')
            ->withTimestamps();
    }

    public function applications(): HasMany
    {
        return $this->hasMany(DepartmentApplication::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function staffUsers(): BelongsToMany
    {
        return $this->users()->wherePivot('role', 'staff');
    }

    public function executiveUsers(): BelongsToMany
    {
        return $this->users()->wherePivot('role', 'executive');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }
}
