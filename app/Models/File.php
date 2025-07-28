<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'portal_id',
        'client_id',
        'project_id',
        'name',
        'original_name',
        'file_path',
        'mime_type',
        'file_size',
        'file_hash',
        'description',
        'metadata',
        'is_public',
        'expires_at',
        'uploaded_by',
        'download_count',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'is_public' => 'boolean',
        'download_count' => 'integer',
        'metadata' => 'array',
        'expires_at' => 'datetime',
    ];

    /**
     * Accessor for backward compatibility with 'path' field.
     */
    public function getPathAttribute()
    {
        return $this->file_path;
    }

    /**
     * Accessor for backward compatibility with 'size' field.
     */
    public function getSizeAttribute()
    {
        return $this->file_size;
    }

    /**
     * Get the portal that owns the file.
     */
    public function portal(): BelongsTo
    {
        return $this->belongsTo(Portal::class);
    }

    /**
     * Get the client that owns the file.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the project that the file belongs to.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who uploaded the file.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Scope a query to only include files for a specific portal.
     */
    public function scopeForPortal($query, $portalId)
    {
        return $query->where('portal_id', $portalId);
    }

    /**
     * Scope a query to only include public files.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Get the file's URL.
     */
    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }

    /**
     * Get the file size in a human-readable format.
     */
    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Check if the file is an image.
     */
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    /**
     * Delete the file from storage when the model is deleted.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($file) {
            Storage::delete($file->path);
        });
    }
}
