<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Task::class => \App\Policies\TaskPolicy::class,
    ];


    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // يمكنك إضافة Gates هنا إن أردت
        // Gate::define('update-task', function ($user, $task) {
        //     return $user->id === $task->assignee_id;
        // });
    }
}
