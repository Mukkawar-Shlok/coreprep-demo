<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Container\Attributes\Log;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log as FacadesLog;

class User extends Authenticatable implements FilamentUser {
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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
    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all test submissions for this user
     */
    public function submissions(): HasMany {
        return $this->hasMany(TestSubmission::class);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool {
        return $this->role === 1;
    }

    /**
     * Check if user can access admin panel (Required by FilamentUser contract)
     */
    public function canAccessPanel(Panel $panel): bool {
        // If user is admin, allow access
        if ($this->isAdmin()) {
            return true;
        }

        // If not admin, clear session and deny access (will redirect to login)
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        return false;
    }
}
