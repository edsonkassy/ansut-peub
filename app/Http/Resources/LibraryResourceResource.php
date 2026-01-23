<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LibraryResourceResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'file_path' => $this->file_path ? asset('storage/' . $this->file_path) : null,
            'file_size' => $this->file_size,
            'mime_type' => $this->mime_type,
            'thumbnail' => $this->thumbnail ? asset('storage/' . $this->thumbnail) : null,
            'url' => $this->url,
            'author' => $this->author,
            'publisher' => $this->publisher,
            'published_date' => $this->published_date?->toDateString(),
            'isbn' => $this->isbn,
            'tags' => $this->tags,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'published_at' => $this->published_at?->toDateString(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            
            // Compteurs
            'likes_count' => $this->when(isset($this->likes_count), $this->likes_count),
            'comments_count' => $this->when(isset($this->comments_count), $this->comments_count),
            'downloads_count' => $this->when(isset($this->downloads_count), $this->downloads_count),
            
            // Relations conditionnelles
            'category' => $this->whenLoaded('category'),
            
            // Informations utilisateur
            'is_favorited' => $this->when(isset($this->is_favorited), $this->is_favorited),
            'is_liked' => $this->when(isset($this->is_liked), $this->is_liked),
        ];
    }
}








