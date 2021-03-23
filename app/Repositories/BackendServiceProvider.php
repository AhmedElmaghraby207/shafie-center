<?php

namespace App\Repositories;

use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider
{

    public function register()
    {
        // Admins
        $this->app->bind(
            'App\Repositories\Admins\AdminsRepositoryInterface',
            'App\Repositories\Admins\AdminsRepository'
        );

    }
}
