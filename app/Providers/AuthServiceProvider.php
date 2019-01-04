<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // закрытие доступа к статистике вебсокетов (/laravel-websockets)
        // пока нет ролей хардкодим адрес почты
        Gate::define('viewWebSocketsDashboard', function ($user) {
            return in_array($user->email, [
                'test@mail.ru'
            ]);
        });
    }
}
