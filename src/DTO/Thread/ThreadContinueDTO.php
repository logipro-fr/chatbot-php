<?php

namespace ChatbotPhp\DTO\Thread;

class ThreadContinueDTO
{
    public string $conversationId;
    public string $message;

    public function __construct(string $conversationId, string $message)
    {
        $this->conversationId = $conversationId;
        $this->message = $message;
    }
}
