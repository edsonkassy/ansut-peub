<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumThread extends Model
{
    use HasFactory;

    protected $fillable = [
        'forum_category_id',
        'user_id',
        'title',
        'slug',
        'content',
        'is_pinned',
        'is_locked',
        'is_featured',
        'views_count',
        'posts_count',
        'last_activity_at',
        'tags'
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
        'is_featured' => 'boolean',
        'last_activity_at' => 'datetime',
        'tags' => 'array'
    ];

    public function category()
    {
        return $this->belongsTo(ForumCategory::class, 'forum_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function posts()
    {
        return $this->hasMany(ForumPost::class);
    }

    public function reactions()
    {
        return $this->morphMany(ForumReaction::class, 'reactable');
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function updateLastActivity()
    {
        $this->update(['last_activity_at' => now()]);
    }

    public function incrementPosts()
    {
        $this->increment('posts_count');
        $this->updateLastActivity();
    }

    public function decrementPosts()
    {
        $this->decrement('posts_count');
    }

    public function getLastPostAttribute()
    {
        return $this->posts()
            ->with(['user'])
            ->latest()
            ->first();
    }

    public function getUserReactionAttribute()
    {
        if (!auth()->check()) return null;
        
        return $this->reactions()
            ->where('user_id', auth()->id())
            ->first();
    }

    public function getReactionCountsAttribute()
    {
        return $this->reactions()
            ->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeNotPinned($query)
    {
        return $query->where('is_pinned', false);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}