<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'foto_profile',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Get the URL for the profile photo
     */
    public function getFotoProfileUrlAttribute(): ?string
    {
        if (!$this->foto_profile) {
            return null;
        }

        // Jika foto_profile sudah berupa URL lengkap
        if (filter_var($this->foto_profile, FILTER_VALIDATE_URL)) {
            return $this->foto_profile;
        }

        // Jika foto_profile hanya nama file, kembalikan path storage
        return asset('storage/profile-photos/' . $this->foto_profile);
    }

    /**
     * Relasi ke produk (jika user bisa memiliki banyak produk)
     */
    public function produks()
    {
        return $this->hasMany(Produk::class, 'id_user');
    }

    /**
     * Scope untuk filter by role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope untuk admin users
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope untuk regular users
     */
    public function scopeRegularUsers($query)
    {
        return $query->where('role', 'user');
    }

    /**
     * Get the default profile photo URL if no photo is uploaded
     */
    public function getDefaultProfilePhotoUrlAttribute(): string
    {
        $name = trim(collect(explode(' ', $this->name))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Get the profile photo URL (with fallback to default)
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->foto_profile_url ?: $this->default_profile_photo_url;
    }
}
