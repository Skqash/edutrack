<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        Paginator::useBootstrapFive();

        // Verify the database connection on boot so any misconfiguration
        // surfaces immediately in the logs rather than as a silent 500.
        try {
            DB::connection()->getPdo();
            Log::info('Database connection established successfully.', [
                'driver'   => config('database.default'),
                'database' => config('database.connections.'.config('database.default').'.database'),
                'host'     => config('database.connections.'.config('database.default').'.host'),
            ]);
        } catch (\Exception $e) {
            Log::critical('Database connection FAILED on boot: '.$e->getMessage(), [
                'driver'    => config('database.default'),
                'host'      => config('database.connections.'.config('database.default').'.host'),
                'database'  => config('database.connections.'.config('database.default').'.database'),
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
