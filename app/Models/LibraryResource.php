<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'library_category_id',
        'user_id',
        'title',
        'slug',
        'description',
        'type',
        'file_path',
        'file_size',
        'mime_type',
        'thumbnail',
        'external_url',
        'duration',
        'author',
        'tags',
        'level',
        'language',
        'views_count',
        'downloads_count',
        'is_featured',
        'is_active',
        'published_at'
    ];

    protected $casts = [
        'tags' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'published_at' => 'datetime'
    ];

    public function category()
    {
        return $this->belongsTo(LibraryCategory::class, 'library_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favorites()
    {
        return $this->hasMany(LibraryFavorite::class);
    }

    public function comments()
    {
        return $this->hasMany(LibraryComment::class)->whereNull('parent_id');
    }

    public function likes()
    {
        return $this->hasMany(LibraryLike::class);
    }

    public function downloads()
    {
        return $this->hasMany(LibraryDownload::class);
    }

    public function isFavoritedBy($user)
    {
        if (!$user) return false;
        return $this->favorites()->where('user_id', $user->id)->exists();
    }

    public function isLikedBy($user)
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function incrementDownloads()
    {
        $this->increment('downloads_count');
    }

    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return $bytes . ' byte';
        } else {
            return '0 bytes';
        }
    }
}