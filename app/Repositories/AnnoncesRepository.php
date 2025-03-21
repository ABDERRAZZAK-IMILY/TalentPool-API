<?php

namespace App\Repositories;

use App\Models\Annonces;
use App\Models\User;

use App\Repositories\Interfaces\AnnoncesRepositoryInterface;


class AnnoncesRepository  implements AnnoncesRepositoryInterface 
{
    protected $model;

    public function __construct()
    {
        $this->model = new User();
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

    public function update(Annonces $annonces, array $data)
    {
        $annonces->update($data);
        return $annonces;
    }
}
