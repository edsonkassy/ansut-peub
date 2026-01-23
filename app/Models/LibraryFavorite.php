<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryFavorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'library_resource_id',
        'user_id'
    ];

    public function resource()
    {
        return $this->belongsTo(LibraryResource::class, 'library_resource_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}