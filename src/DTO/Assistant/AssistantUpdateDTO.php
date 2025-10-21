<?php

namespace ChatbotPhp\DTO\Assistant;

class AssistantUpdateDTO
{
    public string $assistantId;
    /** @var array<string> */
    public array $fileIds;

    /**
     * @param array<string> $fileIds
     */
    public function __construct(string $assistantId, array $fileIds)
    {
        $this->assistantId = $assistantId;
        $this->fileIds = $fileIds;
    }
}
