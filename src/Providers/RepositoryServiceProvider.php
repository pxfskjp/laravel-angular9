<?php

namespace App\Providers;

use App\Repositories\HardwareRepository;
use App\Repositories\SystemRepository;
use App\Repositories\TransferRepository;
use App\Repositories\UserRepository;
use App\Repositories\Interfaces\HardwareRepositoryInterface;
use App\Repositories\Interfaces\SystemRepositoryInterface;
use App\Repositories\Interfaces\TransferRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\User\AuthRepositoryInterface;
use App\Repositories\Interfaces\User\TokenRepositoryInterface;
use App\Repositories\User\AuthRepository;
use App\Repositories\User\TokenRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TokenRepositoryInterface::class, TokenRepository::class);
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(HardwareRepositoryInterface::class, HardwareRepository::class);
        $this->app->bind(SystemRepositoryInterface::class, SystemRepository::class);
        $this->app->bind(TransferRepositoryInterface::class, TransferRepository::class);
    }
}
