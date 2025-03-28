<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class AnnonceController extends Controller
{
    /**
     * Display a listing of all announcements
     * 
     * @OA\Get(
     *     path="/api/annonces",
     *     tags={"Annonces"},
     *     summary="Get all announcements",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Annonce")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json(['data' => Annonce::all()]);
    }

    /**
     * Store a newly created announcement
     * 
     * @OA\Post(
     *     path="/api/annonces",
     *     tags={"Annonces"},
     *     summary="Create new announcement",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Annonce")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Announcement created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Annonce")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $annonce = Annonce::create($request->all());
        return response()->json(['data' => $annonce], 201);
    }

    /**
     * Display the specified announcement
     * 
     * @OA\Get(
     *     path="/api/annonces/{id}",
     *     tags={"Annonces"},
     *     summary="Get announcement by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Annonce")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Announcement not found"
     *     )
     * )
     */
    public function show(Annonce $announcement)
    {
        return response()->json(['data' => $announcement]);
    }

    /**
     * Update the specified announcement
     * 
     * @OA\Put(
     *     path="/api/annonces/{id}",
     *     tags={"Annonces"},
     *     summary="Update announcement",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Annonce")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Announcement updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Annonce")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Announcement not found"
     *     )
     * )
     */
    public function update(Request $request, Annonce $announcement)
    {
        $announcement->update($request->all());
        return response()->json(['data' => $announcement]);
    }

    /**
     * Remove the specified announcement
     * 
     * @OA\Delete(
     *     path="/api/annonces/{id}",
     *     tags={"Annonces"},
     *     summary="Delete announcement",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Announcement deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Announcement not found"
     *     )
     * )
     */
    public function destroy(Annonce $announcement)
    {
        $announcement->delete();
        return response()->json(null, 204);
    }
}
