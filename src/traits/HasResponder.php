<?php

namespace Agunbuhori\Responder\Traits;

use Agunbuhori\Responder\Interfaces\ResponderInterface;
use Agunbuhori\Responder\Transformer;

trait HasResponder
{
    /**
     * Send a success response with the provided data and status code.
     * 
     * @param mixed $data
     * @param mixed $transformer
     * @param mixed $status
     * @param mixed $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data = [], ?Transformer $transformer = null, $status = 200, $message = "success")
    {
        return app(ResponderInterface::class)
            ->data($data, $transformer)
            ->status($status)
            ->message($message)
            ->send();
    }

    /**
     * Send a success response with the provided data and status code.
     * 
     * @param mixed $data
     * @param mixed $transformer
     * @param mixed $status
     * @param mixed $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($status = 400, $message = "error", $data = [])
    {
        return app(ResponderInterface::class)
            ->data($data)
            ->status($status)
            ->message($message)
            ->send();
    }
}