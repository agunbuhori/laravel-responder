<?php

namespace Agunbuhori\Responder;

use Agunbuhori\Responder\Interfaces\ResponderInterface;
use Illuminate\Support\ServiceProvider;
use Agunbuhori\Responder\Commands\GenerateTransformerCommand;

class ResponderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ResponderInterface::class, Responder::class);
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateTransformerCommand::class,
            ]);
        }
    }
}