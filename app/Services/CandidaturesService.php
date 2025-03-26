<?php

namespace App\Services;

use App\Models\Candidature;
use App\Repositories\CandidaturesRepository;

class CandidaturesService
{
    protected $candidatureRepository;

    public function __construct(CandidaturesRepository $candidatureRepository)
    {
        $this->candidatureRepository = $candidatureRepository;
    }

    public function getAll()
    {
        return $this->candidatureRepository->all();
    }

    public function findById($id)
    {
        return $this->candidatureRepository->find($id);
    }

    public function create(array $data)
    {
        $this->candidatureRepository->create($data);
        return ['message' => 'created successfully'];
    }

    public function update($id, array $data)
    {
        return $this->candidatureRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->candidatureRepository->delete($id);
    }
}
