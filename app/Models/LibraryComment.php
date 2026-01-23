<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'library_resource_id',
        'user_id',
        'parent_id',
        'content',
        'is_approved'
    ];

    protected $casts = [
        'is_approved' => 'boolean'
    ];

    public function resource()
    {
        return $this->belongsTo(LibraryResource::class, 'library_resource_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(LibraryComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(LibraryComment::class, 'parent_id');
    }

    public function likes()
    {
        return $this->morphMany(LibraryLike::class, 'likeable');
    }

    public function isLikedBy($user)
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }
}