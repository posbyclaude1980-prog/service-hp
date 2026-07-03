<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'uuid',
        'branch_id',
        'role_id',
        'name',
        'email',
        'password',
        'is_active',
        'phone',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    protected function role(): Attribute
    {
        return Attribute::get(fn () => $this->roleRelation?->slug ?? 'admin');
    }

    public function roleRelation(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function scopeWhereRoleSlug($query, string $slug)
    {
        return $query->whereRelation('roleRelation', 'slug', $slug);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function technicianProfile(): HasOne
    {
        return $this->hasOne(Technician::class);
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKasir(): bool
    {
        return $this->role === 'kasir';
    }

    public function isTeknisi(): bool
    {
        return $this->role === 'teknisi';
    }

    public function canManageUsers(): bool
    {
        return in_array($this->role, ['owner', 'admin'], true);
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            'owner' => 'Owner',
            'admin' => 'Admin',
            'kasir' => 'Kasir',
            'teknisi' => 'Teknisi',
            default => ucfirst((string) $this->role),
        };
    }

    public function statusHistoryChanges(): HasMany
    {
        return $this->hasMany(ServiceOrderStatusHistory::class, 'changed_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function stockMutations(): HasMany
    {
        return $this->hasMany(StockMutation::class);
    }
}
