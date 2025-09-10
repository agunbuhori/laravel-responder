<?php

namespace Agunbuhori\Responder;

use Agunbuhori\Responder\Interfaces\ResponderInterface;
use Illuminate\Support\ServiceProvider;

class ResponderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ResponderInterface::class, Responder::class);
    }
}