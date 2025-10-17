<?php

namespace ChatbotPhp\DTO\Context;

class ContextCreateDTO
{
    public string $contextMessage;

    public function __construct(string $contextMessage)
    {
        $this->contextMessage = $contextMessage;
    }
}
