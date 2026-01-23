<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function threads()
    {
        return $this->hasMany(ForumThread::class);
    }

    public function getThreadsCountAttribute()
    {
        return $this->threads()->count();
    }

    public function getPostsCountAttribute()
    {
        return ForumPost::whereHas('thread', function($query) {
            $query->where('forum_category_id', $this->id);
        })->count();
    }

    public function getLastThreadAttribute()
    {
        return $this->threads()
            ->with(['user', 'category'])
            ->latest('last_activity_at')
            ->first();
    }
}