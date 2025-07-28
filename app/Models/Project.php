<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'portal_id',
        'client_id',
        'name',
        'description',
        'status',
        'start_date',
        'end_date',
        'budget',
        'progress',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'progress' => 'integer',
    ];

    /**
     * Get the portal that owns the project.
     */
    public function portal(): BelongsTo
    {
        return $this->belongsTo(Portal::class);
    }

    /**
     * Get the client that owns the project.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the files for the project.
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    /**
     * Get the invoices for the project.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the milestones associated with the project.
     */
    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class)->ordered();
    }

    /**
     * Scope a query to only include projects for a specific portal.
     */
    public function scopeForPortal($query, $portalId)
    {
        return $query->where('portal_id', $portalId);
    }

    /**
     * Scope a query to only include active projects.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get the progress percentage as a formatted string.
     */
    public function getProgressPercentageAttribute(): string
    {
        return $this->progress . '%';
    }

    /**
     * Check if the project is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->end_date && $this->end_date->isPast() && $this->status !== 'completed';
    }
}
