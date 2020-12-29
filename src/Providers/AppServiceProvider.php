<?php

namespace App\Providers;

use App\Providers\JWT\Signer\Sha256;
use App\Providers\JWT\Signer\Signer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

final class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(RepositoryServiceProvider::class);
        $this->bind();
        $this->registerResources();
    }

    private function registerResources(): void
    {
        $this->app->instance('path.lang', dirname(__DIR__) . '/resources/lang');
        View::addNamespace('api', dirname(__DIR__) . '/resources/views');
        View::addNamespace('web', dirname(__DIR__) . '/resources/views');
    }

    private function bind(): void
    {
        $this->app->bind(Signer::class, Sha256::class);
    }
}
