<?php

namespace App\Providers;

use Illuminate\Console\Scheduling\Schedule;
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
        // Planifier la commande de marquage des factures en retard
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('invoices:mark-overdue')->dailyAt('00:00');
        });
    }
}
