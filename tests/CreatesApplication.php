<?php
namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;

trait CreatesApplication
{

    protected static $migrationsRun = false;

    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        $this->clearCache();
        $this->runMigrations();
        return $app;
    }

    public function clearCache(): void
    {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
    }

    public function runMigrations(): void
    {
        if (! static::$migrationsRun) {
            Artisan::call('migrate', [
                '--path' => '/database/migrations'
            ]);
            static::$migrationsRun = true;
        }
    }
}
