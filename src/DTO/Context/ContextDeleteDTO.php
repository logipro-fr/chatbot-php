<?php

namespace ChatbotPhp\DTO\Context;

class ContextDeleteDTO
{
    public string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
