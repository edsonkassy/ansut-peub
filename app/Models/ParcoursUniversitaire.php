<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcoursUniversitaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'bachelier_id',
        'universite_nom',
        'pays',
        'niveau',
        'annee_academique',
        'performance',
        'mention',
        'attestation_admission_file',
        'extracted_data',
        'statut',
    ];

    protected $casts = [
        'extracted_data' => 'array',
        'performance' => 'decimal:2',
    ];

    public function bachelier()
    {
        return $this->belongsTo(Bachelier::class);
    }
} 