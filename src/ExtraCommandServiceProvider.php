<?php

namespace YangYiYi\ExtraCommand;

use Illuminate\Support\ServiceProvider;

class ExtraCommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/extra-command.php' => config_path('extra-command.php'),
            ]);

            $this->commands([
                Console\ServiceMakeCommand::class,
                Console\FacadeMakeCommand::class
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/extra-command.php',
            'extra-command'
        );
    }
}
