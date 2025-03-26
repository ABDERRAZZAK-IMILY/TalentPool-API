<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Candidatures;

class CandidaturesPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isCandidat() || $user->isRecruiter() || $user->isAdmin();
    }

    public function view(User $user, Candidatures $candidature): bool
    {
        return $user->id === $candidature->candidat_id 
            || $user->id === $candidature->annonce->recruteur_id 
            || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isCandidat();
    }

    public function update(User $user, Candidatures $candidature): bool
    {
        return $user->id === $candidature->candidat_id || $user->isAdmin();
    }

    public function delete(User $user, Candidatures $candidature): bool
    {
        return $user->id === $candidature->candidat_id || $user->isAdmin();
    }
}
