<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ForumThreadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'content' => $this->content,
            'tags' => $this->tags,
            'is_pinned' => $this->is_pinned,
            'is_locked' => $this->is_locked,
            'is_published' => $this->is_published,
            'views_count' => $this->views_count,
            'last_activity_at' => $this->last_activity_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            
            // Compteurs
            'posts_count' => $this->when(isset($this->posts_count), $this->posts_count),
            
            // Relations conditionnelles
            'category' => $this->whenLoaded('category'),
            'user' => new UserResource($this->whenLoaded('user')),
            'posts' => ForumPostResource::collection($this->whenLoaded('posts')),
            
            // Informations utilisateur
            'is_favorited' => $this->when(isset($this->is_favorited), $this->is_favorited),
        ];
    }
}








