<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'avatar_path',
        'bio',
        'banner_path',
        'subscription_price',
        'split_account_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'subscription_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    // ── Relationships ──

    public function packs()
    {
        return $this->hasMany(Pack::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function subscriptionsAsSubscriber()
    {
        return $this->hasMany(Subscription::class, 'subscriber_id');
    }

    public function subscriptionsAsCreator()
    {
        return $this->hasMany(Subscription::class, 'creator_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // ── Helpers ──

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCreator(): bool
    {
        return $this->role === 'creator';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar_path) {
            return asset('storage/' . $this->avatar_path);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=e91e8c&color=fff&size=200';
    }

    public function getBannerUrlAttribute(): string
    {
        if ($this->banner_path) {
            return asset('storage/' . $this->banner_path);
        }
        return '';
    }

    public function hasPurchased(Pack $pack): bool
    {
        return $this->purchases()->where('pack_id', $pack->id)->where('status', 'confirmed')->exists();
    }

    public function isSubscribedTo(User $creator): bool
    {
        return $this->subscriptionsAsSubscriber()
            ->where('creator_id', $creator->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->exists();
    }

    public function hasAccessToPack(Pack $pack): bool
    {
        if ($this->id === $pack->user_id) return true;
        if ($this->isAdmin()) return true;
        if ($this->hasPurchased($pack)) return true;
        if ($this->isSubscribedTo($pack->user)) return true;
        return false;
    }

    public function getActiveSubscribersCountAttribute(): int
    {
        return $this->subscriptionsAsCreator()
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->count();
    }

    public function getTotalEarningsAttribute(): float
    {
        return $this->transactions()
            ->where('status', 'completed')
            ->sum('creator_amount');
    }
}
