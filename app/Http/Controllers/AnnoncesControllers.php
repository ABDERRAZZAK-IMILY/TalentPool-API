<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnnonceRequest;
use App\Services\AnnonceService;
use Illuminate\Http\Response;

class AnnoncesControllers extends Controller
{
    protected $annonceService;

    public function __construct(AnnonceService $annonceService)
    {
        $this->annonceService = $annonceService;
    }

    public function index()
    {
        return response()->json($this->annonceService->getAllAnnonces());
    }

    public function store(AnnonceRequest $request)
    {
        $annonce = $this->annonceService->createAnnonce($request->validated());
        return response()->json($annonce, Response::HTTP_CREATED);
    }

    public function show($id)
    {
        return response()->json($this->annonceService->getAnnonce($id));
    }

    public function update(AnnonceRequest $request, $id)
    {
        $annonce = $this->annonceService->updateAnnonce($id, $request->validated());
        return response()->json($annonce);
    }

    public function destroy($id)
    {
        $this->annonceService->deleteAnnonce($id);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
