<?php

namespace App\Providers;

use App\Data\Models\Hardware;
use App\Data\Models\System;
use App\Data\Models\Transfer;
use App\Data\Models\User;
use App\Policies\HardwarePolicy;
use App\Policies\SystemPolicy;
use App\Policies\TransferPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as FrameworkAuthServiceProvider;

final class AuthServiceProvider extends FrameworkAuthServiceProvider
{
    /**
     *
     * @var array $policies
     */
    protected $policies = [
        Hardware::class => HardwarePolicy::class,
        System::class => SystemPolicy::class,
        User::class => UserPolicy::class,
        Transfer::class => TransferPolicy::class
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
