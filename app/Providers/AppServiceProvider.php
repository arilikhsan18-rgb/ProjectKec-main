<?php

namespace App\Providers;

use App\Models\Report; // <-- Import
use App\Policies\ReportPolicy; // <-- Import
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Report::class => ReportPolicy::class, // <-- Daftarkan di sini
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Beri akses superadmin & kecamatan untuk semua hal
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('SUPERADMIN') || $user->hasRole('KECAMATAN')) {
                return true;
            }
        });
    }
}
