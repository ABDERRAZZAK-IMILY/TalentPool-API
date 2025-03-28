<?php

namespace App\Services;

use App\Repositories\AnnoncesRepository;
use App\Repositories\CandidaturesRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class StatisticsService
{
    protected $annoncesRepository;
    protected $candidaturesRepository;
    protected $userRepository;

    /**
     * Create a new service instance.
     */
    public function __construct(
        AnnoncesRepository $annoncesRepository,
        CandidaturesRepository $candidaturesRepository,
        UserRepository $userRepository
    ) {
        $this->annoncesRepository = $annoncesRepository;
        $this->candidaturesRepository = $candidaturesRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Get statistics for a specific recruiter
     * 
     * @param int $recruiterId The ID of the recruiter
     * @return array Statistics data for the recruiter
     */
    public function getRecruiterStatistics($recruiterId)
    {
        // Get all annonces for this recruiter
        $annonces = DB::table('annonces')
            ->where('recruteur_id', $recruiterId)
            ->get();
        
        $annonceIds = $annonces->pluck('id')->toArray();
        
        // Get all candidatures for these annonces
        $candidatures = DB::table('candidatures')
            ->whereIn('annonce_id', $annonceIds)
            ->get();
        
        // Count candidatures by status
        $statusCounts = [
            'en_attente' => 0,
            'acceptée' => 0,
            'refusée' => 0,
        ];
        
        foreach ($candidatures as $candidature) {
            if (isset($statusCounts[$candidature->statut])) {
                $statusCounts[$candidature->statut]++;
            }
        }
        
        // Calculate statistics by time periods
        $lastMonth = now()->subMonth();
        $lastWeek = now()->subWeek();
        
        $recentAnnonces = $annonces->filter(function ($annonce) use ($lastMonth) {
            return strtotime($annonce->created_at) >= $lastMonth->timestamp;
        })->count();
        
        $recentCandidatures = $candidatures->filter(function ($candidature) use ($lastWeek) {
            return strtotime($candidature->created_at) >= $lastWeek->timestamp;
        })->count();
        
        // Calculate response rate (percentage of non-pending applications)
        $responseRate = $candidatures->count() > 0
            ? round((($statusCounts['acceptée'] + $statusCounts['refusée']) / $candidatures->count()) * 100, 2)
            : 0;
        
        // Calculate acceptance rate
        $acceptanceRate = ($statusCounts['acceptée'] + $statusCounts['refusée']) > 0
            ? round(($statusCounts['acceptée'] / ($statusCounts['acceptée'] + $statusCounts['refusée'])) * 100, 2)
            : 0;
        
        return [
            'total_annonces' => $annonces->count(),
            'total_candidatures' => $candidatures->count(),
            'candidatures_by_status' => $statusCounts,
            'annonces_last_month' => $recentAnnonces,
            'candidatures_last_week' => $recentCandidatures,
            'response_rate' => $responseRate,
            'acceptance_rate' => $acceptanceRate,
            'average_candidatures_per_annonce' => $annonces->count() > 0
                ? round($candidatures->count() / $annonces->count(), 2)
                : 0,
        ];
    }

    /**
     * Get global statistics for administrators
     * 
     * @return array Global statistics data
     */
    public function getAdminStatistics()
    {
        // Count users by role
        $userCounts = [
            'total' => $this->userRepository->count(),
            'recruiters' => $this->userRepository->getByRole('recruteur')->count(),
            'candidates' => $this->userRepository->getByRole('candidat')->count(),
            'admins' => $this->userRepository->getByRole('admin')->count(),
        ];
        
        // Count total annonces and candidatures
        $totalAnnonces = DB::table('annonces')->count();
        $totalCandidatures = DB::table('candidatures')->count();
        
        // Count candidatures by status
        $statusCounts = DB::table('candidatures')
            ->select('statut', DB::raw('count(*) as count'))
            ->groupBy('statut')
            ->pluck('count', 'statut')
            ->toArray();
        
        // Ensure all statuses are represented
        $allStatusCounts = [
            'en_attente' => $statusCounts['en_attente'] ?? 0,
            'acceptée' => $statusCounts['acceptée'] ?? 0,
            'refusée' => $statusCounts['refusée'] ?? 0,
        ];
        
        // Calculate statistics by time periods
        $lastMonth = now()->subMonth()->toDateTimeString();
        $lastWeek = now()->subWeek()->toDateTimeString();
        
        $recentAnnonces = DB::table('annonces')
            ->where('created_at', '>=', $lastMonth)
            ->count();
        
        $recentCandidatures = DB::table('candidatures')
            ->where('created_at', '>=', $lastWeek)
            ->count();
        
        // Calculate user growth
        $newUsers = DB::table('users')
            ->where('created_at', '>=', $lastMonth)
            ->count();
        
        $userGrowthRate = $userCounts['total'] > 0
            ? round(($newUsers / $userCounts['total']) * 100, 2)
            : 0;
        
        // Calculate platform activity metrics
        $activeRecruiters = DB::table('annonces')
            ->distinct('recruteur_id')
            ->where('created_at', '>=', $lastMonth)
            ->count('recruteur_id');
        
        $activeCandidates = DB::table('candidatures')
            ->distinct('candidat_id')
            ->where('created_at', '>=', $lastMonth)
            ->count('candidat_id');
        
        // Calculate global response and acceptance rates
        $responseRate = $totalCandidatures > 0
            ? round((($allStatusCounts['acceptée'] + $allStatusCounts['refusée']) / $totalCandidatures) * 100, 2)
            : 0;
        
        $acceptanceRate = ($allStatusCounts['acceptée'] + $allStatusCounts['refusée']) > 0
            ? round(($allStatusCounts['acceptée'] / ($allStatusCounts['acceptée'] + $allStatusCounts['refusée'])) * 100, 2)
            : 0;
        
        return [
            'user_counts' => $userCounts,
            'total_annonces' => $totalAnnonces,
            'total_candidatures' => $totalCandidatures,
            'candidatures_by_status' => $allStatusCounts,
            'annonces_last_month' => $recentAnnonces,
            'candidatures_last_week' => $recentCandidatures,
            'new_users_last_month' => $newUsers,
            'user_growth_rate' => $userGrowthRate,
            'active_recruiters_last_month' => $activeRecruiters,
            'active_candidates_last_month' => $activeCandidates,
            'platform_response_rate' => $responseRate,
            'platform_acceptance_rate' => $acceptanceRate,
            'average_candidatures_per_annonce' => $totalAnnonces > 0
                ? round($totalCandidatures / $totalAnnonces, 2)
                : 0,
        ];
    }

    /**
     * Get statistics for a specific job posting
     * 
     * @param int $annonceId The ID of the job posting
     * @return array Statistics data for the job posting
     */
    public function getAnnonceStatistics($annonceId)
    {
        // Get the annonce
        $annonce = DB::table('annonces')->find($annonceId);
        
        if (!$annonce) {
            return ['error' => 'Annonce not found'];
        }
        
        // Get all candidatures for this annonce
        $candidatures = DB::table('candidatures')
            ->where('annonce_id', $annonceId)
            ->get();
        
        // Count candidatures by status
        $statusCounts = [
            'en_attente' => 0,
            'acceptée' => 0,
            'refusée' => 0,
        ];
        
        foreach ($candidatures as $candidature) {
            if (isset($statusCounts[$candidature->statut])) {
                $statusCounts[$candidature->statut]++;
            }
        }
        
        // Calculate time-based metrics
        $createdAt = strtotime($annonce->created_at);
        $daysSinceCreation = ceil((time() - $createdAt) / (60 * 60 * 24));
        
        $candidaturesPerDay = $daysSinceCreation > 0
            ? round($candidatures->count() / $daysSinceCreation, 2)
            : $candidatures->count();
        
        // Calculate response rate
        $responseRate = $candidatures->count() > 0
            ? round((($statusCounts['acceptée'] + $statusCounts['refusée']) / $candidatures->count()) * 100, 2)
            : 0;
        
        return [
            'annonce_id' => $annonceId,
            'titre' => $annonce->titre,
            'total_candidatures' => $candidatures->count(),
            'candidatures_by_status' => $statusCounts,
            'days_since_creation' => $daysSinceCreation,
            'candidatures_per_day' => $candidaturesPerDay,
            'response_rate' => $responseRate,
        ];
    }
}