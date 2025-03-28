<?php

namespace App\Http\Controllers;

use App\Services\StatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class AdminController extends Controller
{
    protected $statisticsService;

   
    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }


    public function getGlobalStatistics()
    {
        if (!Auth::isAdmin()) {
            return response()->json(['error' => 'Unauthorized. Admin access required.'], 403);
        }

        $statistics = $this->statisticsService->getAdminStatistics();
        
        return response()->json([
            'data' => $statistics
        ]);
    }

    public function getRecruiterStatistics($recruiterId)
    {
        if (!Auth::isAdmin()) {
            return response()->json(['error' => 'Unauthorized. Admin access required.'], 403);
        }

        $statistics = $this->statisticsService->getRecruiterStatistics($recruiterId);
        
        return response()->json([
            'data' => $statistics
        ]);
    }

   
    public function getAnnonceStatistics($annonceId)
    {
        if (!Auth::isAdmin()) {
            return response()->json(['error' => 'Unauthorized. Admin access required.'], 403);
        }

        $statistics = $this->statisticsService->getAnnonceStatistics($annonceId);
        
        return response()->json([
            'data' => $statistics
        ]);
    }

 
    public function getUserManagementData()
    {
        if (!Auth::isAdmin()) {
            return response()->json(['error' => 'Unauthorized. Admin access required.'], 403);
        }

        $userRepository = app()->make('App\Repositories\UserRepository');
        
        $users = [
            'recruiters' => $userRepository->getByRole('recruteur'),
            'candidates' => $userRepository->getByRole('candidat'),
            'admins' => $userRepository->getByRole('admin'),
        ];
        
        return response()->json([
            'data' => $users
        ]);
    }
}