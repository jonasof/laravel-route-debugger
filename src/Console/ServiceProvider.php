<?php

namespace LaravelRouteFinder\Console;

use Illuminate\Support\ServiceProvider as SP;

class ServiceProvider extends SP
{
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                FindRouteCommand::class,
            ]);
        }
    }
}
