<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        if ($this->app->environment('production') || isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            URL::forceScheme('https');
        }

        // Otomatis pastikan akun Admin default ada di database SQLite Railway/Production
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('users') && \App\Models\User::count() === 0) {
                \App\Models\User::create([
                    'name' => 'Administrator Karang Taruna',
                    'email' => 'admin@jendela.desa.id',
                    'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                    'peran_pengguna' => 'Admin Perpustakaan',
                ]);
            }
        } catch (\Throwable $e) {
            // Ignore during initial migration execution
        }
    }
}
