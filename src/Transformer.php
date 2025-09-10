<?php

namespace Agunbuhori\Responder;

use Agunbuhori\Responder\Interfaces\TransformerInterface;
use Exception;

abstract class Transformer implements TransformerInterface
{
    public function handle($data)
    {
        if (!method_exists($this, 'transform')) {
            throw new Exception('Transformer must implement the transform method.');
        }

        return $this->transform($data);
    }
}
