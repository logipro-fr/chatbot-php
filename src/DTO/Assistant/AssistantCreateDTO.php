<?php

namespace ChatbotPhp\DTO\Assistant;

class AssistantCreateDTO
{
    public string $contextId;
    /** @var array<string> */
    public array $fileIds;

    /**
     * @param array<string> $fileIds
     */
    public function __construct(string $contextId, array $fileIds = [])
    {
        $this->contextId = $contextId;
        $this->fileIds = $fileIds;
    }
}
