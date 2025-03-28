<?php

namespace App\Http\Controllers;

use App\Services\CandidaturesService;
use App\Http\Requests\CandidaturesRequest;
use App\Mail\StatusChange;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use OpenApi\Annotations as OA;

class CandidaturesController extends Controller
{
    protected $candidaturesService;

    public function __construct(CandidaturesService $candidaturesService)
    {
        $this->candidaturesService = $candidaturesService;
    }

      /**
     * @OA\Get(
     *     path="/api/candidatures",
     *     tags={"Candidatures"},
     *     summary="Get all candidatures",
     *     @OA\Response(
     *         response=200,
     *         description="List of candidatures",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Candidature")
     *         )
     *     )
     * )
     */

    public function index(): JsonResponse
    {
        $candidatures = $this->candidaturesService->getAll();
        return response()->json($candidatures);
    }

     /**
     * @OA\Get(
     *     path="/api/candidatures/{id}",
     *     tags={"Candidatures"},
     *     summary="Get candidature by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Candidature details",
     *         @OA\JsonContent(ref="#/components/schemas/Candidature")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Candidature not found"
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        $candidature = $this->candidaturesService->findById($id);
        if (!$candidature) {
            return response()->json(['message' => 'Candidature not found'], 404);
        }
        return response()->json($candidature);
    }

     /**
     * @OA\Post(
     *     path="/api/candidatures",
     *     tags={"Candidatures"},
     *     summary="Create new candidature",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Candidature")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Candidature created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Candidature")
     *     )
     * )
     */

    public function store(CandidaturesRequest $request): JsonResponse
    {
        $result = $this->candidaturesService->create($request->validated());
        return response()->json($result, 201);
    }


     /**
     * @OA\Put(
     *     path="/api/candidatures/{id}",
     *     tags={"Candidatures"},
     *     summary="Update candidature",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Candidature")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Candidature updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Candidature not found"
     *     )
     * )
     */
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


     /**
     * @OA\Delete(
     *     path="/api/candidatures/{id}",
     *     tags={"Candidatures"},
     *     summary="Delete candidature",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Candidature deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Candidature not found"
     *     )
     * )
     */

    public function destroy($id): JsonResponse
    {
        $result = $this->candidaturesService->delete($id);
        if (!$result) {
            return response()->json(['message' => 'Candidature not found'], 404);
        }
        return response()->json(['message' => 'Deleted successfully']);
    }
}
