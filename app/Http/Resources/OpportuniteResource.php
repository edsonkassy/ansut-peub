<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OpportuniteResource extends JsonResource
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
            'partenaire_id' => $this->partenaire_id,
            'titre' => $this->titre,
            'description' => $this->description,
            'type_opportunite' => $this->type_opportunite,
            'domaine' => $this->domaine,
            'niveau_etudes' => $this->niveau_etudes,
            'duree' => $this->duree,
            'localisation' => $this->localisation,
            'mode' => $this->mode,
            'date_debut' => $this->date_debut?->toDateString(),
            'date_fin' => $this->date_fin?->toDateString(),
            'date_limite_candidature' => $this->date_limite_candidature?->toDateString(),
            'nombre_places' => $this->nombre_places,
            'criteres_eligibilite' => $this->criteres_eligibilite,
            'documents_requis' => $this->documents_requis,
            'avantages' => $this->avantages,
            'processus_selection' => $this->processus_selection,
            'contact_info' => $this->contact_info,
            'url_externe' => $this->url_externe,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'views_count' => $this->views_count,
            'series_acceptees' => $this->series_acceptees,
            'moyenne_minimum' => $this->moyenne_minimum,
            'regions_ciblees' => $this->regions_ciblees,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            
            // Relations conditionnelles
            'partenaire' => new PartenaireResource($this->whenLoaded('partenaire')),
            'types' => $this->whenLoaded('types'),
            
            // Données additionnelles
            'compatibility_score' => $this->when(isset($this->compatibility_score), $this->compatibility_score),
            'can_apply' => $this->when(isset($this->can_apply), $this->can_apply),
            'has_applied' => $this->when(isset($this->has_applied), $this->has_applied),
        ];
    }
}








