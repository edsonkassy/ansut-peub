<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidatureResource extends JsonResource
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
            'bachelier_id' => $this->bachelier_id,
            'opportunite_id' => $this->opportunite_id,
            'lettre_motivation' => $this->lettre_motivation,
            'status' => $this->status,
            'status_text' => ucfirst(str_replace('_', ' ', $this->status)),
            'documents' => $this->documents,
            'notes_partenaire' => $this->notes_partenaire,
            'date_reponse' => $this->date_reponse?->toDateString(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            
            // Relations conditionnelles
            'opportunite' => new OpportuniteResource($this->whenLoaded('opportunite')),
            'bachelier' => new BachelierResource($this->whenLoaded('bachelier')),
        ];
    }
}








