<?php

namespace App\Providers;

use App\Models\Aprendiz;
use App\Models\User;
use App\Policies\AprendizPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Aprendiz::class => AprendizPolicy::class,
    ];

    public function boot(): void
    {
        Gate::define('manage-users', fn (User $user): bool => $user->isAdmin());
    }
}
