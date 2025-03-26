<?php

namespace App\Repositories;

use App\Models\Annonce;
use App\Repositories\Interfaces\AnnoncesRepositoryInterface;

class AnnoncesRepository implements AnnoncesRepositoryInterface 
{
    protected $model;

    public function __construct(Annonce $annonces)
    {
        $this->model = $annonces;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function all()
    {
        return $this->model->all();
    }

    public function update($id, array $data)
    {
        $annonce = $this->find($id);
        if ($annonce) {
            $annonce->update($data);
            return $annonce;
        }
        return null;
    }

    public function delete($id)
    {
        $annonce = $this->find($id);
        if ($annonce) {
            return $annonce->delete();
        }
        return false;
    }
}
