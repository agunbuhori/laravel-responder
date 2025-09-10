<?php

use Agunbuhori\Responder\Interfaces\ResponderInterface;

if (!function_exists('responder')) {
    function responder(): ResponderInterface
    {
        return app(ResponderInterface::class);
    }
}