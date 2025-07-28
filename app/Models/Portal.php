<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Portal extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'subdomain',
        'custom_domain',
        'description',
        'logo_path',
        'primary_color',
        'secondary_color',
        'branding_settings',
        'portal_settings',
        'is_active',
    ];

    protected $casts = [
        'branding_settings' => 'array',
        'portal_settings' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($portal) {
            if (empty($portal->slug)) {
                $portal->slug = Str::slug($portal->name);
            }
        });
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    // Helper methods
    public function getUrlAttribute(): string
    {
        if ($this->custom_domain) {
            return 'https://' . $this->custom_domain;
        }
        
        if ($this->subdomain) {
            return 'https://' . $this->subdomain . '.portalprohub.com';
        }
        
        return route('portals.show', $this->slug);
    }

    public function getBrandingAttribute(): array
    {
        return array_merge([
            'primary_color' => $this->primary_color,
            'secondary_color' => $this->secondary_color,
            'logo_path' => $this->logo_path,
        ], $this->branding_settings ?? []);
    }
}
