<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = [
        'business_id',
        'department_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'document_type',
        'status',
        'rejection_reason',
        'uploaded_by',
        'verified_at',
        'metadata',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function getSignedUrlAttribute(): ?string
    {
        if (!$this->file_path) {
            return null;
        }
        return \Storage::disk('secure_documents')->temporaryUrl(
            $this->file_path,
            now()->addHours(24)
        );
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('document_type', $type);
    }
}
