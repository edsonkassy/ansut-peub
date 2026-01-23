<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParcoursUniversitaireResource extends JsonResource
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
            'etablissement' => $this->etablissement,
            'niveau' => $this->niveau,
            'filiere' => $this->filiere,
            'annee_debut' => $this->annee_debut,
            'annee_fin' => $this->annee_fin,
            'diplome_obtenu' => $this->diplome_obtenu,
            'moyenne' => $this->moyenne,
            'mention' => $this->mention,
            'en_cours' => $this->en_cours,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}








