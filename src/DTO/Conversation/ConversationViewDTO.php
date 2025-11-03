<?php

namespace ChatbotPhp\DTO\Conversation;

class ConversationViewDTO
{
    public string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
