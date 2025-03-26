<?php

namespace App\Services;

use App\Models\Annonce;
use Illuminate\Database\Eloquent\Collection;

class AnnonceService
{
    public function getAllAnnonces(): Collection
    {
        return Annonce::all();
    }

    public function createAnnonce(array $data): Annonce
    {
        return Annonce::create($data);
    }

    public function getAnnonce(int $id): Annonce
    {
        return Annonce::findOrFail($id);
    }

    public function updateAnnonce(int $id, array $data): Annonce
    {
        $annonce = $this->getAnnonce($id);
        $annonce->update($data);
        return $annonce;
    }

    public function deleteAnnonce(int $id): bool
    {
        $annonce = $this->getAnnonce($id);
        return $annonce->delete();
    }
}
