<?php

namespace Ecd\Feedbacktool;

use Illuminate\Support\ServiceProvider;

class FeedbackToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/routes.php');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Ecd\Feedbacktool\Controllers\IssuesController');

        $this->app->singleton('ecd.feedbacktool.console.kernel', function($app) {
            $dispatcher = $app->make(\Illuminate\Contracts\Events\Dispatcher::class);
            return new \Ecd\Feedbacktool\Console\Kernel($app, $dispatcher);
        });

        $this->app->make('ecd.feedbacktool.console.kernel');
    }
}
