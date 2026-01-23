<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartenaireOpportuniteType extends Model
{
    protected $fillable = [
        'partenaire_id',
        'type_opportunite'
    ];

    public function partenaire()
    {
        return $this->belongsTo(Partenaire::class);
    }
} 