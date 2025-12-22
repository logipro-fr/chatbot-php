<?php

namespace ChatbotPhp\Resources;

use ChatbotPhp\DTO\Assistant\AssistantCreateDTO;
use ChatbotPhp\DTO\Assistant\AssistantDeleteDTO;
use ChatbotPhp\DTO\Assistant\AssistantAttachFileDTO;
use ChatbotPhp\DTO\Assistant\AssistantViewDTO;
use ChatbotPhp\Services\AssistantService;

class Assistants
{
    private AssistantService $assistantService;

    public function __construct(AssistantService $assistantService)
    {
        $this->assistantService = $assistantService;
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function create(array $parameters): string
    {
        /** @var string $contextId */
        $contextId = $parameters['context_id'];
        $fileIds = [];
        if (isset($parameters['file_ids']) && is_array($parameters['file_ids'])) {
            foreach ($parameters['file_ids'] as $fileId) {
                /** @var string $fileId */
                $fileId = $fileId;
                $fileIds[] = $fileId;
            }
        }
        $dto = new AssistantCreateDTO($contextId, $fileIds);

        return $this->assistantService->create($dto);
    }

    public function retrieve(string $assistantId): string
    {
        $dto = new AssistantViewDTO($assistantId);

        return $this->assistantService->view($dto);
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function attachFiles(array $parameters): string
    {
        /** @var string $assistantId */
        $assistantId = $parameters['assistant_id'];
        $fileIds = [];
        if (isset($parameters['file_ids']) && is_array($parameters['file_ids'])) {
            foreach ($parameters['file_ids'] as $fileId) {
                /** @var string $fileId */
                $fileId = $fileId;
                $fileIds[] = $fileId;
            }
        }
        $dto = new AssistantAttachFileDTO($assistantId, $fileIds);

        return $this->assistantService->update($dto);
    }

    public function delete(string $assistantId): string
    {
        $dto = new AssistantDeleteDTO($assistantId);

        return $this->assistantService->delete($dto);
    }
}
