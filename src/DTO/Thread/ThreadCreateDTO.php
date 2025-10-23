<?php

namespace ChatbotPhp\DTO\Thread;

class ThreadCreateDTO
{
    public string $astId;
    public string $message;

    public function __construct(string $astId, string $message)
    {
        $this->astId = $astId;
        $this->message = $message;
    }
}
