<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pack extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'cover_image_path',
        'price',
        'is_active',
        'is_featured',
        'downloads_count',
        'views_count',
        'media_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pack) {
            if (empty($pack->slug)) {
                $pack->slug = Str::slug($pack->title) . '-' . Str::random(6);
            }
        });
    }

    // ── Relationships ──

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class)->orderBy('sort_order');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // ── Helpers ──

    public function getCoverUrlAttribute(): string
    {
        if ($this->cover_image_path) {
            return asset('storage/' . $this->cover_image_path);
        }

        // Use first media image as cover
        $firstImage = $this->media()->where('file_type', 'image')->first();
        if ($firstImage) {
            return asset('storage/' . $firstImage->file_path);
        }

        return asset('images/placeholder-pack.jpg');
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'R$ ' . number_format($this->price, 2, ',', '.');
    }

    public function getTotalSalesAttribute(): int
    {
        return $this->purchases()->where('status', 'confirmed')->count();
    }

    public function getTotalRevenueAttribute(): float
    {
        return $this->purchases()->where('status', 'confirmed')->sum('amount_paid');
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
