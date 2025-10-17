<?php

namespace ChatbotPhp\DTO\Context;

class ContextUpdateDTO
{
    public string $id;
    public string $newMessage;

    public function __construct(string $id, string $newMessage)
    {
        $this->id = $id;
        $this->newMessage = $newMessage;
    }
}
