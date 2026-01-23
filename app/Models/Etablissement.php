<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etablissement extends Model
{
    protected $fillable = [
        'drena',
        'commune',
        'code_etab',
        'etablissement',
        'type_etab',
    ];
    
    public function bacheliers()
    {
        return $this->hasMany(Bachelier::class, 'etablissement_id');
    }
}
