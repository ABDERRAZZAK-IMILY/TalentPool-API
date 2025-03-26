<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use Illuminate\Http\Request;

class AnnonceController extends Controller
{
    public function index()
    {
        return response()->json(['data' => Annonce::all()]);
    }

    public function store(Request $request)
    {
        $annonce = Annonce::create($request->all());
        return response()->json(['data' => $annonce], 201);
    }

    public function show(Annonce $announcement)
    {
        return response()->json(['data' => $announcement]);
    }

    public function update(Request $request, Annonce $announcement)
    {
        $announcement->update($request->all());
        return response()->json(['data' => $announcement]);
    }

    public function destroy(Annonce $announcement)
    {
        $announcement->delete();
        return response()->json(null, 204);
    }
}
