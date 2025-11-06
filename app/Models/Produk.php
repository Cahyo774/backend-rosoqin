<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'address',
        'latitude',
        'longitude',
        'price',
        'status',
        'photo',
        'id_user'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship dengan user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Scope untuk produk available
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope untuk produk unavailable
     */
    public function scopeUnavailable($query)
    {
        return $query->where('status', 'unavailable');
    }

    /**
     * Scope untuk filter by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope untuk pencarian title dan description
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Scope untuk filter by location (radius dalam km)
     */
    public function scopeNearby($query, $latitude, $longitude, $radius = 10)
    {
        return $query->selectRaw(
            "*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance",
            [$latitude, $longitude, $latitude]
        )->having('distance', '<', $radius)
         ->orderBy('distance');
    }

    /**
     * Get photo URL attribute
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo) {
            return null;
        }

        // Jika photo sudah berupa URL lengkap
        if (filter_var($this->photo, FILTER_VALIDATE_URL)) {
            return $this->photo;
        }

        // Jika photo hanya nama file, kembalikan path storage
        return asset('storage/produk-photos/' . $this->photo);
    }

    /**
     * Format price dengan Rupiah
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get truncated description (untuk preview)
     */
    public function getShortDescriptionAttribute(): string
    {
        return strlen($this->description) > 100
            ? substr($this->description, 0, 100) . '...'
            : $this->description;
    }

    /**
     * Check if product is available
     */
    public function getIsAvailableAttribute(): bool
    {
        return $this->status === 'available';
    }

    /**
     * Check if product has coordinates
     */
    public function getHasCoordinatesAttribute(): bool
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }

    /**
     * Get coordinates as array
     */
    public function getCoordinatesAttribute(): array
    {
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ];
    }

    /**
     * Get map URL (Google Maps)
     */
    public function getMapUrlAttribute(): ?string
    {
        if (!$this->has_coordinates) {
            return null;
        }

        return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
    }
}
