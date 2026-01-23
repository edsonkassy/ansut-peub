<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'forum_thread_id',
        'user_id',
        'parent_id',
        'content',
        'is_approved',
        'edited_at',
        'edited_by'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'edited_at' => 'datetime'
    ];

    public function thread()
    {
        return $this->belongsTo(ForumThread::class, 'forum_thread_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(ForumPost::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(ForumPost::class, 'parent_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }

    public function reactions()
    {
        return $this->morphMany(ForumReaction::class, 'reactable');
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

    public function getIsEditedAttribute()
    {
        return !is_null($this->edited_at);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($post) {
            // Incrémenter le nombre de posts du thread
            $post->thread->incrementPosts();
        });

        static::deleted(function ($post) {
            // Décrémenter le nombre de posts du thread
            $post->thread->decrementPosts();
        });
    }
}