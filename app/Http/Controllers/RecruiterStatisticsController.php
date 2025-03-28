<?php

namespace App\Http\Controllers;

use App\Services\StatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class RecruiterStatisticsController extends Controller
{
    protected $statisticsService;

    
    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

   
    public function getMyStatistics()
    {
        $user = Auth::user();
        
        if (!Auth::isRecruiter()) {
            return response()->json(['error' => 'Unauthorized. Recruiter access required.'], 403);
        }

        $statistics = $this->statisticsService->getRecruiterStatistics($user->id);
        
        return response()->json([
            'data' => $statistics
        ]);
    }

    
    public function getAnnonceStatistics($annonceId)
    {
        $user = Auth::user();
        
        if (!Auth::isRecruiter()) {
            return response()->json(['error' => 'Unauthorized. Recruiter access required.'], 403);
        }

        $annonceRepository = app()->make('App\Repositories\AnnoncesRepository');
        $annonce = $annonceRepository->find($annonceId);
        
        if (!$annonce || $annonce->recruteur_id != $user->id) {
            return response()->json(['error' => 'Unauthorized. This job posting does not belong to you.'], 403);
        }

        $statistics = $this->statisticsService->getAnnonceStatistics($annonceId);
        
        return response()->json([
            'data' => $statistics
        ]);
    }

   
    public function getApplicationStatusDistribution()
    {
        $user = Auth::user();
        
        if (!Auth::isRecruiter()) {
            return response()->json(['error' => 'Unauthorized. Recruiter access required.'], 403);
        }

        $statistics = $this->statisticsService->getRecruiterStatistics($user->id);
        
        return response()->json([
            'data' => [
                'status_distribution' => $statistics['candidatures_by_status'],
                'total' => $statistics['total_candidatures']
            ]
        ]);
    }

    
    public function getRecentActivity()
    {
        $user = Auth::user();
        
        if (!Auth::isRecruiter()) {
            return response()->json(['error' => 'Unauthorized. Recruiter access required.'], 403);
        }

        $statistics = $this->statisticsService->getRecruiterStatistics($user->id);
        
        return response()->json([
            'data' => [
                'annonces_last_month' => $statistics['annonces_last_month'],
                'candidatures_last_week' => $statistics['candidatures_last_week'],
                'response_rate' => $statistics['response_rate'],
                'acceptance_rate' => $statistics['acceptance_rate']
            ]
        ]);
    }
}