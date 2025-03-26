<?php

namespace App\Repositories\Interfaces;

interface CandidaturesRepositoryInterface
{
    public function create(array $data);
    public function find($id);
    public function all();
    public function update($id, array $data);
    public function delete($id);
}
