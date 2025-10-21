<?php

namespace ChatbotPhp\DTO\Assistant;

class AssistantDeleteDTO
{
    public string $assistantId;

    public function __construct(string $assistantId)
    {
        $this->assistantId = $assistantId;
    }
}
