<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function packs()
    {
        return $this->hasMany(Pack::class);
    }

    public function activePacksCount(): int
    {
        return $this->packs()->where('is_active', true)->count();
    }
}
