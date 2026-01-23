<?php

namespace App\Policies;

use App\Models\ParcoursUniversitaire;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ParcoursUniversitairePolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ParcoursUniversitaire $parcoursUniversitaire): bool
    {
        return $user->bachelier && $user->bachelier->id === $parcoursUniversitaire->bachelier_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ParcoursUniversitaire $parcoursUniversitaire): bool
    {
        return $user->bachelier && $user->bachelier->id === $parcoursUniversitaire->bachelier_id;
    }
}
