<?php

namespace Agunbuhori\Responder;

use Agunbuhori\Responder\Interfaces\TransformerInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class Transformer implements TransformerInterface
{
    public function handle(mixed $data): mixed
    {
        if (!method_exists($this, 'transform')) {
            throw new Exception('Transformer must implement the transform method.');
        }

        if ($data instanceof Collection) {
            return $data->map(fn ($item) => $this->transform($item))->toArray();
        } else if (is_array($data) && !$this->isAssoc($data)) {
            return collect($data)->map(fn ($item) => $this->transform($item))->toArray();
        }

        return $this->transform($data);
    }

    private function isAssoc(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }
}
