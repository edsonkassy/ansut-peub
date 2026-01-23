<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BachelierResource extends JsonResource
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
            
            // Informations personnelles
            'nom' => $this->nom,
            'prenoms' => $this->prenoms,
            'nom_complet' => $this->nom_complet,
            'date_naissance' => $this->date_naissance?->toDateString(),
            'lieu_naissance' => $this->lieu_naissance,
            'sexe' => $this->sexe,
            'age' => $this->age,
            
            // Contact
            'telephone_eleve' => $this->telephone_eleve,
            'telephone_parent' => $this->telephone_parent,
            'email_eleve' => $this->email_eleve,
            'email_parent' => $this->email_parent,
            
            // Localisation
            'region' => $this->region,
            'commune' => $this->commune,
            
            // Informations académiques
            'matricule_bac' => $this->matricule_bac,
            'serie_bac' => $this->serie_bac,
            'note_bac' => $this->note_bac,
            'mention' => $this->mention,
            'etablissement_nom' => $this->etablissement_nom,
            'etablissement_type' => $this->etablissement_type,
            'annee_bac' => $this->annee_bac,
            
            // Situation socio-économique
            'pensionnaire_internat' => $this->pensionnaire_internat,
            'bourse_scolaire_lycee' => $this->bourse_scolaire_lycee,
            'profession_pere' => $this->profession_pere,
            'profession_mere' => $this->profession_mere,
            'situations_particulieres' => $this->situations_particulieres,
            'possede_ordinateur' => $this->possede_ordinateur,
            'connexion_internet' => $this->connexion_internet,
            'acces_smartphone' => $this->acces_smartphone,
            'acces_ia' => $this->acces_ia,
            
            // Motivations et compétences
            'motivation' => $this->motivation,
            'projet_professionnel' => $this->projet_professionnel,
            'competences' => $this->competences,
            'langues' => $this->langues,
            'bio' => $this->bio,
            
            // Fichiers
            'photo' => $this->photo ? asset('storage/' . $this->photo) : null,
            'cv_path' => $this->cv_path ? asset('storage/' . $this->cv_path) : null,
            'piece_identite_file' => $this->piece_identite_file ? asset('storage/' . $this->piece_identite_file) : null,
            'collante_bac_file' => $this->collante_bac_file ? asset('storage/' . $this->collante_bac_file) : null,
            
            // Statut PEUB
            'boursier_peub' => $this->boursier_peub,
            'date_integration_peub' => $this->date_integration_peub?->toDateString(),
            'status_candidature' => $this->status_candidature,
            'status_candidature_text' => $this->statut_candidature_text,
            'status_profil' => $this->status_profil,
            'status_profil_text' => $this->statut_profil_text,
            'date_verification' => $this->date_verification?->toDateString(),
            
            // Scoring PEUB
            'score_academique' => $this->score_academique,
            'score_geographique' => $this->score_geographique,
            'score_socio_economique' => $this->score_socio_economique,
            'score_motivations' => $this->score_motivations,
            'score_final_peub' => $this->score_final_peub,
            'rang_peub' => $this->rang_peub,
            'is_in_top_2000' => $this->isInTop2000(),
            
            // Timestamps
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            
            // Relations conditionnelles
            'parcours_universitaires' => ParcoursUniversitaireResource::collection($this->whenLoaded('parcoursUniversitaires')),
        ];
    }
}








