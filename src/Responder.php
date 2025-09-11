<?php

namespace Agunbuhori\Responder;

use Agunbuhori\Responder\Interfaces\ResponderInterface;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class Responder implements ResponderInterface
{
    private mixed $data = [];

    private int $status = 200;

    private string|array $message = 'success';

    private bool $withoutWrapper = false;

    private ?Transformer $transformer = null;

    public function data(mixed $data, Transformer|string|null $transformer = null): self
    {
        $this->transformer = is_string($transformer) ? new $transformer : $transformer;
        $this->data = $data;
        return $this;
    }

    public function status(int $status): self
    {
        $this->status = $status;
        return $this;
    }
    
    public function message(string|array $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function withoutWrapper(): self
    {
        $this->withoutWrapper = true;
        return $this;
    }

    public function send(): JsonResponse
    {
        $data = $this->transformData($this->data);

        return $this->withoutWrapper 
        ? response()->json($data) 
        : response()->json([
            'data' => $data,
            'status' => $this->status,
            'message' => $this->message,
        ]);
    }

    public function exception(): void
    {
        throw new HttpResponseException(response()->json([
            'data' => $this->data,
            'status' => $this->status,
            'message' => $this->message,
        ]));
    }

    private function transformData(mixed $data): mixed
    {
        if (!$this->transformer) return $data;

        return $this->transformer->handle($data);
    }
}