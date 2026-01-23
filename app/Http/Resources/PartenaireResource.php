<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartenaireResource extends JsonResource
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
            'user_id' => $this->user_id,
            'nom_organisation' => $this->nom_organisation,
            'type_organisation' => $this->type_organisation,
            'secteur_activite' => $this->secteur_activite,
            'description' => $this->description,
            'adresse' => $this->adresse,
            'ville' => $this->ville,
            'pays' => $this->pays,
            'telephone' => $this->telephone,
            'email_organisation' => $this->email_organisation,
            'site_web' => $this->site_web,
            'personne_contact_nom' => $this->personne_contact_nom,
            'personne_contact_fonction' => $this->personne_contact_fonction,
            'personne_contact_telephone' => $this->personne_contact_telephone,
            'personne_contact_email' => $this->personne_contact_email,
            'logo' => $this->logo ? asset('storage/' . $this->logo) : null,
            'status_verification' => $this->status_verification,
            'date_verification' => $this->date_verification?->toDateString(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}








