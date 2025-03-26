<?php

namespace App\Providers;

use App\Models\Annonce;
use App\Models\Candidatures;
use App\Models\User;
use App\Policies\AnnoncesPolicy;
use App\Policies\CandidaturesPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policies
        Gate::policy(Annonce::class, AnnoncesPolicy::class);
        Gate::policy(Candidatures::class, CandidaturesPolicy::class);

        // Role-based gates
        Gate::define('isAdmin', fn(User $user) => $user->isAdmin());
        Gate::define('isRecruiter', fn(User $user) => $user->isRecruiter());
        Gate::define('isCandidat', fn(User $user) => $user->isCandidat());

        // Model-specific gates
        Gate::define('manage-annonces', fn(User $user) => 
            $user->isRecruiter() || $user->isAdmin()
        );

        Gate::define('manage-candidatures', fn(User $user, Candidatures $candidature) => 
            $user->id === $candidature->candidat_id || 
            $user->isAdmin()
        );

        Gate::define('view-candidature-details', fn(User $user, Candidatures $candidature) => 
            $user->id === $candidature->candidat_id || 
            $user->id === $candidature->annonce->recruteur_id || 
            $user->isAdmin()
        );

        Gate::define('create-annonce', fn(User $user) => 
            $user->isRecruiter() || $user->isAdmin()
        );
    }
}
