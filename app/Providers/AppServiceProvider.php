<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
        Paginator::useBootstrapFour();

        // Ensure font directory exists for DomPDF
        if (!file_exists(storage_path('fonts'))) {
            @mkdir(storage_path('fonts'), 0775, true);
        }

        // Ensure dompdf always resolves a valid existing directory for public_path on any hosting environment (cPanel, VPS, etc.)
        $configuredPath = config('dompdf.public_path');
        if (!$configuredPath || !is_dir($configuredPath) || realpath($configuredPath) === false) {
            $candidates = [
                public_path(),
                base_path('public'),
                base_path('public_html'),
                base_path('../public_html'),
                base_path(),
            ];

            foreach ($candidates as $candidate) {
                if ($candidate && is_dir($candidate) && realpath($candidate) !== false) {
                    config(['dompdf.public_path' => realpath($candidate)]);
                    break;
                }
            }
        }
    }
}
