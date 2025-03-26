<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Annonce;

class AnnoncesPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Public access to view job listings
    }

    public function view(User $user, Annonce $annonce): bool
    {
        return true; // Public access to view individual job listing
    }

    public function create(User $user): bool
    {
        return $user->isRecruiter() || $user->isAdmin();
    }

    public function update(User $user, Annonce $annonce): bool
    {
        return ($user->id === $annonce->recruteur_id && $user->isRecruiter()) || $user->isAdmin();
    }

    public function delete(User $user, Annonce $annonce): bool
    {
        return ($user->id === $annonce->recruteur_id && $user->isRecruiter()) || $user->isAdmin();
    }
}
