<?php

namespace App\Providers;

use App\Models\Aprendiz;
use App\Policies\AprendizPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Aprendiz::class => AprendizPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
