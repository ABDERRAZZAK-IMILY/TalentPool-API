<?php

namespace App\Http\Controllers;

use App\Services\CandidaturesService;
use App\Http\Requests\CandidaturesRequest;
use App\Mail\StatusChange;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class CandidaturesController extends Controller
{
    protected $candidaturesService;

    public function __construct(CandidaturesService $candidaturesService)
    {
        $this->candidaturesService = $candidaturesService;
    }

    public function index(): JsonResponse
    {
        $candidatures = $this->candidaturesService->getAll();
        return response()->json($candidatures);
    }

    public function show($id): JsonResponse
    {
        $candidature = $this->candidaturesService->findById($id);
        if (!$candidature) {
            return response()->json(['message' => 'Candidature not found'], 404);
        }
        return response()->json($candidature);
    }

    public function store(CandidaturesRequest $request): JsonResponse
    {
        $result = $this->candidaturesService->create($request->validated());
        return response()->json($result, 201);
    }

    public function update(CandidaturesRequest $request, $id): JsonResponse
    {
        $updated = $this->candidaturesService->update($id, $request->validated());
        if (!$updated) {
            return response()->json(['message' => 'Candidature not found'], 404);
        }
        
        $result = $this->candidaturesService->findById($id);
        
        Mail::to($result->email)->send(new StatusChange($result));

        return response()->json(['message' => 'Updated successfully']);
    }

    public function destroy($id): JsonResponse
    {
        $result = $this->candidaturesService->delete($id);
        if (!$result) {
            return response()->json(['message' => 'Candidature not found'], 404);
        }
        return response()->json(['message' => 'Deleted successfully']);
    }
}
