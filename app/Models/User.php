<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser, HasName, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_EDITOR = 'editor';
    public const ROLE_PUBLISHER = 'publisher';
    public const ROLE_SUBSCRIBER = 'subscriber';

    public const ROLES = [
        self::ROLE_SUPER_ADMIN => 'Super Admin',
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_EDITOR => 'Editor',
        self::ROLE_PUBLISHER => 'Publisher / Reporter',
        self::ROLE_SUBSCRIBER => 'Subscriber / Member',
    ];

    protected static function booted(): void
    {
        static::updating(function (User $user) {
            $authUser = auth()->user();
            // If authenticated user is not super admin and target user is super admin, forbid update
            if ($authUser && ! $authUser->isSuperAdmin() && $user->getOriginal('role') === self::ROLE_SUPER_ADMIN) {
                abort(403, 'Administrators cannot edit Super Admin accounts.');
            }
        });

        static::deleting(function (User $user) {
            $authUser = auth()->user();
            // If authenticated user is not super admin and target user is super admin, forbid deletion
            if ($authUser && ! $authUser->isSuperAdmin() && $user->isSuperAdmin()) {
                abort(403, 'Administrators cannot delete Super Admin accounts.');
            }
        });
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, [
            self::ROLE_SUPER_ADMIN,
            self::ROLE_ADMIN,
            self::ROLE_EDITOR,
            self::ROLE_PUBLISHER,
        ], true);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN], true);
    }

    public function isEditor(): bool
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN, self::ROLE_EDITOR], true);
    }

    public function isPublisher(): bool
    {
        return $this->role === self::ROLE_PUBLISHER;
    }

    public function isSubscriber(): bool
    {
        return $this->role === self::ROLE_SUBSCRIBER || empty($this->role);
    }

    public function hasRole(string|array $roles): bool
    {
        $roles = (array) $roles;
        return in_array($this->role, $roles, true);
    }

    public function getRoleLabelAttribute(): string
    {
        return self::ROLES[$this->role] ?? 'Subscriber';
    }

    public function getFilamentName(): string
    {
        return $this->name ?: 'कपिलवस्तु दर्पण';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'phone',
        'bio',
        'avatar',
        'password',
        'email_verified_at',
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
     * Get avatar URL or null.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->avatar) {
            return str_starts_with($this->avatar, 'http')
                ? $this->avatar
                : asset('storage/' . $this->avatar);
        }

        return null;
    }

    /**
     * Get user's joined date in Nepali format.
     */
    public function getNepaliJoinedDateAttribute(): string
    {
        return $this->created_at ? nepali_date($this->created_at) : '२०८१';
    }

    /**
     * Get comments authored by the user.
     */
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    /**
     * Get articles authored by the user.
     */
    public function articles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Article::class)->latest();
    }

    /**
     * Reassign all authored articles to another user.
     */
    public function reassignArticlesTo(User|int $targetUser): int
    {
        $targetId = is_numeric($targetUser) ? $targetUser : $targetUser->id;
        return $this->articles()->update(['user_id' => $targetId]);
    }
}
