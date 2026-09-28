<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PalmaresBac extends Model
{
    protected $table = 'palmares_bac';

    protected $fillable = [
        'matricule', 'nom', 'prenoms', 'date_naissance', 'lieu_naissance', 'drena',
        'sexe', 'serie', 'mention', 'etablissement', 'points', 'rang_drena', 'annee_bac',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'points' => 'integer',
        'rang_drena' => 'integer',
    ];

    public static function normalizeMatricule(?string $matricule): string
    {
        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) $matricule));
    }
}
