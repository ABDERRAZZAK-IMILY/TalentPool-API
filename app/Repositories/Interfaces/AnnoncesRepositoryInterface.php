<?php


namespace App\Repositories\Interfaces;
use App\Models\Annonces;


interface AnnoncesRepositoryInterface {


    public function create(array $data);

    public function find($id);

    public function all();
    public function update(Annonces $annonces, array $data);
    

}