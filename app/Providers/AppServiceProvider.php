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
        // Otomatis buat struktur folder storage jika belum ada (saat Volume Railway baru di-mount)
        $storageFolders = [
            storage_path('app/public'),
            storage_path('framework/cache/data'),
            storage_path('framework/sessions'),
            storage_path('framework/testing'),
            storage_path('framework/views'),
            storage_path('logs'),
        ];
        foreach ($storageFolders as $folder) {
            if (!is_dir($folder)) {
                @mkdir($folder, 0777, true);
            }
        }

        // Pastikan berkas SQLite tersimpan di dalam folder storage permanen
        $dbPath = storage_path('database.sqlite');
        if (!file_exists($dbPath)) {
            @touch($dbPath);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production') || isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            URL::forceScheme('https');
        }

        // Otomatis migrasi & pastikan akun Admin default ada di database SQLite Railway/Production
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('users')) {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            }

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
