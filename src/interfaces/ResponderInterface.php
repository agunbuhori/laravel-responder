<?php

namespace Agunbuhori\Responder\Interfaces;

use Agunbuhori\Responder\Transformer;
use Illuminate\Http\JsonResponse;

interface ResponderInterface
{
    /**
     * Set the data to be transformed and optionally provide a transformer.
     * 
     * @param mixed $data
     * @param mixed $transformer
     * @return self
     */
    public function data(mixed $data, Transformer|string|null $transformer = null): self;

    /**
     * Set the status code for the response.
     * 
     * @param int $status
     * @return void
     */
    public function status(int $status): self;

    /**
     * Set the message for the response.
     * 
     * @param string $message
     * @return void
     */
    public function message(string $message): self;

    /**
     * Set whether the response should be wrapped in a JSON object.
     * 
     * @return void
     */
    public function withoutWrapper(): self;

    /**
     * Send the response.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(): JsonResponse;

    /**
     * Handle any exceptions that occur during the response process.
     * 
     * @return void
     */
    public function exception(): void;
}