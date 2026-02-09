<?php

namespace App\Providers;

use App\Models\SystemSetting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        // Share system settings to all views (safe when migrating)
        try {
            $settings = SystemSetting::query()->firstOrCreate([], [
                'system_name' => 'Photobooth',
                'payment_required' => true,
            ]);
        }
        catch (\Throwable $e) {
            $settings = null;
        }

        View::share('systemSettings', $settings);
    }
}