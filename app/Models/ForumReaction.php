<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumReaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reactable_type',
        'reactable_id',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reactable()
    {
        return $this->morphTo();
    }

    public static function getReactionTypes()
    {
        return [
            'like' => [
                'name' => 'J\'aime',
                'icon' => 'thumbs-up',
                'color' => 'text-blue-600'
            ],
            'love' => [
                'name' => 'J\'adore',
                'icon' => 'heart',
                'color' => 'text-red-600'
            ],
            'wow' => [
                'name' => 'Wow',
                'icon' => 'star',
                'color' => 'text-yellow-600'
            ],
            'angry' => [
                'name' => 'Pas cool',
                'icon' => 'frown',
                'color' => 'text-orange-600'
            ],
            'sad' => [
                'name' => 'Triste',
                'icon' => 'meh',
                'color' => 'text-gray-600'
            ]
        ];
    }

    public function getReactionDataAttribute()
    {
        $reactions = self::getReactionTypes();
        return $reactions[$this->type] ?? null;
    }
}