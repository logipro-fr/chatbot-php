<?php

namespace ChatbotPhp\DTO\Assistant;

class AssistantViewDTO
{
    public string $assistantId;

    public function __construct(string $assistantId)
    {
        $this->assistantId = $assistantId;
    }
}
