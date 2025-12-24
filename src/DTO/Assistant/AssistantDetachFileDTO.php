<?php

namespace ChatbotPhp\DTO\Assistant;

class AssistantDetachFileDTO
{
    public string $assistantId;
    public string $fileId;

    public function __construct(string $assistantId, string $fileId)
    {
        $this->assistantId = $assistantId;
        $this->fileId = $fileId;
    }
}
