<?php

namespace ChatbotPhp\DTO\Assistant;

class AssistantAttachFileDTO
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
