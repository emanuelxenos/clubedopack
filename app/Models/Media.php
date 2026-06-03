<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'pack_id',
        'file_path',
        'thumbnail_path',
        'file_type',
        'size',
        'sort_order',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function pack()
    {
        return $this->belongsTo(Pack::class);
    }

    public function getUrlAttribute(): string
    {
        return \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'media.stream',
            now()->addHours(6),
            [
                'media' => $this->id,
                'ip' => request()->ip(),
                'uid' => auth()->id()
            ]
        );
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail_path) {
            if (file_exists(storage_path('app/private/' . $this->thumbnail_path))) {
                return route('media.show', $this);
            }
            return \Illuminate\Support\Facades\Storage::disk('local')->url($this->thumbnail_path);
        }
        return $this->url;
    }

    public function isImage(): bool
    {
        return $this->file_type === 'image';
    }

    public function isVideo(): bool
    {
        return $this->file_type === 'video';
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }
}
