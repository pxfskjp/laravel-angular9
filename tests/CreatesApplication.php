<?php
namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

trait CreatesApplication
{

    protected static $migrationsRun = false;

    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        $this->createDatabaseIfNotExists();
        $this->clearCache();
        $this->runMigrations();
        return $app;
    }

    private function clearCache(): void
    {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
    }

    private function runMigrations(): void
    {
        if (! static::$migrationsRun) {
            Artisan::call('migrate');
            static::$migrationsRun = true;
        }
    }

    private function createDatabaseIfNotExists(): void
    {
        $dbName = config('database.connections.mysql.database');
        DB::connection('mysql_without_database')
            ->getPdo()
            ->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}`");
    }
}
