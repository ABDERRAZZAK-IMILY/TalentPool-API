<?php

namespace App\Repositories;

use App\Models\Candidatures;
use App\Repositories\Interfaces\CandidaturesRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CandidaturesRepository implements CandidaturesRepositoryInterface
{
    protected $model;

    public function __construct(Candidatures $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find($id): ?Candidatures
    {
        return $this->model->find($id);
    }

    public function create(array $data): Candidatures
    {
        return $this->model->create($data);
    }

    public function update($id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function delete($id): bool
    {
        return $this->model->where('id', $id)->delete();
    }
}
