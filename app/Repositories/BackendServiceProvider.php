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

        // Notifications
        $this->app->bind(
            'App\Repositories\Notifications\NotificationsRepositoryInterface',
            'App\Repositories\Notifications\NotificationsRepository'
        );

        // Notification Templates
        $this->app->bind(
            'App\Repositories\NotificationTemplates\NotificationTemplatesRepositoryInterface',
            'App\Repositories\NotificationTemplates\NotificationTemplatesRepository'
        );
    }
}
