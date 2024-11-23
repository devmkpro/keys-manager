<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\HasCodeTrait;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens, HasCodeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'password' => 'hashed'
        ];
    }

    /**
     * Determine if the user has the given role.
     *
     * @param string $role
     * @return bool
     */
    public function isOwner(): bool
    {
        return $this->hasRole('owner');
    }

    /**
     * Determine if the user has the given role.
     *
     * @param string $role
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Generate a random code for the user.
     *
     * @return string
     */
    public function generateCode(): string
    {
        $code =  'VMUSR-' . strtoupper(bin2hex(random_bytes(2)));
        if (static::where('code', $code)->exists()) {
            return $this->generateCode();
        }

        return $code;
    }

    /**
     * Determine if the user can access the given panel.
     *
     * @param Panel $panel
     * @return bool
     */

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole([
            'owner',
            'admin'
        ]);
    }
}
