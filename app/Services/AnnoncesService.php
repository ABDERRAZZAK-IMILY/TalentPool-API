<?php
namespace App\Services;

use App\Models\Annonces;
use App\Repositories\AnnoncesRepository;

class AnnonceService
{
  
    protected $AnnonceReposotory;

    public function __construct(AnnoncesRepository $AnnonceReposotory)
    {
        $this->AnnonceReposotory = $AnnonceReposotory;
    }

    public function getAllAnnonces()
    {
        return $this->AnnonceReposotory->all();
    }

    public function getAnnonceById($id)
    {
        return $this->AnnonceReposotory->find($id);
    }

    public function createAnnonce(array $data,$recruteur_id)
    {
        $data['user_id'] = $recruteur_id;
       $this->AnnonceReposotory->create($data);
      return ['message' => 'createed succe'];
    }
    
    public function updateAnnonce($id, array $data)
    {
    
    }

    public function deleteAnnonce($id)
    {
      
    }
}